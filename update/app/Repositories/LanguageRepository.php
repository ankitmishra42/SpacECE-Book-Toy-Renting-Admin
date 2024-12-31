<?php

namespace App\Repositories;

use App\Http\Requests\LanguageRequest;
use App\Models\Language;

class LanguageRepository extends Repository
{
    public function model()
    {
        return Language::class;
    }

    public function checkFileExitsOrNot(array $fileNames)
    {
        foreach ($fileNames as $name) {
            if (! $this->isNameExists($name)) {
                $this->create([
                    'title' => $name,
                    'name' => $name,
                ]);
            }
        }
    }

    public function storeByRequest(LanguageRequest $request)
    {
        $filePath = base_path("lang/$request->name.json");

        $jsonData = file_get_contents(public_path('web/emptyLanguage.json'));

        file_put_contents($filePath, $jsonData);

        return $this->create([
            'title' => $request->title,
            'name' => $request->name,
        ]);
    }

    public function updateByRequest(Language $language, LanguageRequest $request, $filePath): Language
    {
        $keys = $request->key;
        $values = $request->value;

        $newData = array_combine($keys, $values);

        $existingData = json_decode(file_get_contents($filePath), true);

        $updatedData = array_merge($existingData, $newData);

        file_put_contents($filePath, json_encode($updatedData, JSON_PRETTY_PRINT));

        $language->update([
            'title' => $request->title,
        ]);

        return $language;
    }

    public function isNameExists($name)
    {
        return $this->query()->where('name', $name)->exists();
    }
}
