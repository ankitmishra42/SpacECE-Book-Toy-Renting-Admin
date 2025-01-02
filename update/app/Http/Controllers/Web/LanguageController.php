<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\LanguageRequest;
use App\Models\Language;
use App\Repositories\LanguageRepository;
use Illuminate\Support\Facades\File;

class LanguageController extends Controller
{
    public function index()
    {
        $folderPath = base_path('lang'); // actual folder path

        if (File::isDirectory($folderPath)) {
            $files = File::allFiles($folderPath);

            $fileNames = [];
            foreach ($files as $file) {
                $fileName = $file->getFilenameWithoutExtension();
                if ($fileName != 'installer_messages') {
                    $fileNames[] = $fileName;
                }
            }

            (new LanguageRepository())->checkFileExitsOrNot($fileNames);

            $languages = (new LanguageRepository())->getAll();

            return view('language.index', compact('languages'));
        }

        return back()->withError('Base folder lang not found');
    }

    public function create()
    {
        return view('language.create');
    }

    public function store(LanguageRequest $request)
    {
        $path = base_path('lang/').$request->name.'.json';

        if (! file_exists($path)) {
            (new LanguageRepository())->storeByRequest($request);

            return to_route('language.index')->withSuccess('Created Successfully');
        }

        return back()->withError('language already exits');
    }

    public function edit(Language $language)
    {
        $filePath = base_path('lang/').$language->name.'.json'; // directory
        if (file_exists($filePath)) {
            $languageData = json_decode(file_get_contents($filePath), true);

            return view('language.edit', compact('languageData', 'language'));
        }

        return back()->withError('Base folder or file not found');
    }

    public function update(LanguageRequest $request, Language $language)
    {
        $filePath = base_path('lang/').$language->name.'.json'; // directory

        if (file_exists($filePath)) {

            (new LanguageRepository())->updateByRequest($language, $request, $filePath);

            return to_route('language.index')->withSuccess('Updated Successfully');
        }

        return back()->withError('File does not exist');
    }

    public function delete(Language $language)
    {
        $filePath = base_path('lang/').$language->name.'.json'; // directory
        if (file_exists($filePath) && $language->name != 'en') {
            unlink($filePath);
            (new LanguageRepository())->delete($language->id);

            return to_route('language.index')->withSuccess('Deleted Successfully');
        }

        return back()->withError('File does not exist');
    }
}
