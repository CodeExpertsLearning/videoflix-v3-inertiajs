<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { loadStripe } from '@stripe/stripe-js';
import { ref } from 'vue';

import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';

defineProps({
    stripe_intent: Object,
    cashier_key: String,
});

const inputEl = ref(null);
const stripeSecret = usePage().props.stripe_intent.client_secret;
const stripe = loadStripe(usePage().props.cashier_key);
const cardName = ref(null);

let cardElement = '';

stripe.then((instStripe) => {
    const elements = instStripe.elements({
        stripeSecret,
        appearance: {
            rules: {
                '.Input': {
                    colorText: '#fff',
                    backgroundColor: 'white',
                },
            },
        },
    });

    cardElement = elements.create('card', {
        style: {
            base: {
                iconColor: '#c4f0ff',
                color: '#A3A3A3',
                fontSmoothing: 'antialiased',
                ':-webkit-autofill': {
                    color: '#A3A3A3',
                },
                '::placeholder': {
                    color: '#A3A3A3',
                },
            },
        },
    });

    cardElement.mount(inputEl.value);
});

const charge = () => {
    stripe.then(async (instStripe) => {
        const { setupIntent, error } = await instStripe.confirmCardSetup(stripeSecret, {
            payment_method: {
                card: cardElement,
                billing_details: {
                    name: cardName.value,
                },
            },
        });

        if (error) {
            //exibir o erro pro usuário...
            console.log(error);
        } else {
            router.post(route('checkout.charge'), {
                _token: usePage().props.csrf_token,
                paymentMethod: setupIntent.payment_method,
            });
        }
    });
};
</script>

<template>
    <AuthLayout title="Inicie Sua Assiantura" description="Realize a assinatura para acessar nossos vídeos...">
        <div class="">
            <Label>Nome Cartão</Label>
            <Input placeholder="Nome conforme seu cartão..." v-model="cardName" />
            <div id="card" ref="inputEl" class="rouded mb-10 w-full border border-gray-600 px-6 py-3"></div>
            <button
                @click="charge"
                class="rounded border border-green-900 bg-green-700 px-4 py-2 font-thin text-white transition duration-300 ease-in-out hover:bg-green-900"
            >
                Assinar
            </button>
        </div>
    </AuthLayout>
</template>
