<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Jobs\VideoCreateThumbJob;
use App\Jobs\VideoEncondingProcessJob;
use App\Models\Content;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Pion\Laravel\ChunkUpload\Handler\ContentRangeUploadHandler;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class VideoController extends Controller
{
    public function index(Content $content)
    {
        return inertia('media/VideoUpload', compact('content'));
    }

    public function store(Request $request, Content $content)
    {
        $video = $content->videos()->create([
            'code' => Str::uuid(),
            'name' => $request->name,
        ]);

        return $video;
    }

    public function processChunk(Content $content, string $video, Request $request)
    {
        $receiver = new FileReceiver(
            UploadedFile::fake()->createWithContent('file', $request->getContent()),
            $request,
            ContentRangeUploadHandler::class
        );

        $save = $receiver->receive();

        if ($save->isFinished()) {

            $video = $content->videos()->findOrFail($video);
            $video->update([
                'video' => $save->getFile()->storeAs('', Str::uuid(), 'videos')
            ]);

            Bus::batch([
                new VideoCreateThumbJob($video),
                new VideoEncondingProcessJob($video)
            ])->allowFailures()->dispatch();
        }

        $save->handler();
    }

    public function destroy(Content $content, $video)
    {
        $video = $content->videos()->findOrFail($video);
        $video->delete();

        return redirect()->back();
    }
}
