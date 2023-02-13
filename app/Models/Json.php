<?php

namespace App\Models;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Collection;


class Json
{
    protected mixed $data;

    public function __construct()
    {
        $this->data = $this->readData();
    }

    protected function readData(): Collection
    {
        $path = storage_path('data');
        $data = new Collection();
        if (File::isDirectory($path)) {
            $files = File::files($path);
            foreach ($files as $file) {
                if (File::exists($file)) {
                    $data->push(json_decode(File::get($file), true));
                }
            }
        }
        return $data;
    }
}
