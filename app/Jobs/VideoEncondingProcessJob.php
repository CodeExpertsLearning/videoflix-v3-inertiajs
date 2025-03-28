<?php

namespace App\Jobs;

use App\Models\Video;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use FFMpeg\Format\Video\X264;

class VideoEncondingProcessJob implements ShouldQueue
{
    use Queueable, Batchable;

    /**
     * Create a new job instance.
     */
    public function __construct(private Video $video) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //To-DO: Propagar inicio do encoding via broadcast por meio do Reverb(Websocket)

        $videoNewName =  str_replace(strrchr($this->video->video, '.'), '', $this->video->video) . '.m3u8';

        $lowBitrateFormat  = (new X264)->setKiloBitrate(500);
        $midBitrateFormat  = (new X264)->setKiloBitrate(1500);
        $highBitrateFormat = (new X264)->setKiloBitrate(3000);

        FFMpeg::fromDisk('videos')
            ->open($this->video->video)
            ->exportForHLS()
            ->addFormat($lowBitrateFormat)
            ->addFormat($midBitrateFormat)
            ->addFormat($highBitrateFormat)
            ->onProgress(function ($progress) {
                //TO-DO: Propagar este progresso via broadcast por meio do Reverb(Websocket)
            })
            ->toDisk('videos_processed')
            ->save($this->video->code . '/' . $videoNewName);

        Storage::disk('videos')->delete($this->video->video);

        $this->video->update([
            'video' => $videoNewName,
            'is_processed' => true
        ]);

        //To-DO: Propagar conclusão encoding via broadcast por meio do Reverb(Websocket)
    }
}
