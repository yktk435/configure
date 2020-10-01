<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Session\Store;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\Storage;
//phpspreadsheet
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as Wxlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Rxlsx;



use Symfony\Component\VarDumper\VarDumper;

class ConfigController extends Controller
{

    function index()
    {
        // $c = new Configure();
        // print_r($c->interface);
        // $this->excelTest();
        $items = ['items' => 'テスト'];
        return view('configure.index', $items);
    }
    // ダウンロードさせる
    private function change(Request $request)
    {
        $files = $request->file(); //アップロードされたファイルを変数に代入

        foreach ($files as $file) {
            //     $ext=$request->file('file')->getClientOriginalExtension(); //拡張子を取得

            //ファイルのオリジナルファイル名を取得
            $name = $file->getClientOriginalName();

            //アップロードされたファイルが storage/file になかったら保存
            if (Storage::disk('local')->missing('file/' . $name)) {
                print "保存";

                //保存  strage/app/file に保存される
                $file->storeAs('file', $name);
            } else {
                print "保存済み";
            }
            //保存したファイルのフルパスを取得
            $uploadedFilePaths[] = Storage::disk('local')->path('file/' . $name);
        }

        // 複数のコンフィグファイルを変換して1つのエクセルに変換して、そのファイルのパスを返す
        $excelFilePath = $this->toExcelFile($uploadedFilePaths);

        return Storage::download($excelFilePath);
    }
    private function filePathToArray($path)
    {
        $c = new Configure($path);
    }
    private function toExcelFile(array $uploadedFilePaths): string
    {
        //変換後のエクセルファイルのパス
        $excelFilePath = '';

        foreach ($uploadedFilePaths as $path) {
            $config = new Configure($path);
        }
        return $excelFilePath;
    }
    private function excelTest()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');

        $writer = new Wxlsx($spreadsheet);
        $writer->save('hello world.xlsx');
    }
    function test()
    {
        // シートのタイトル用の名前
        $sheetTitle = 'sheetTitle';
        $fileName = 'filename';
        // テンプレートエクセルシートをコピー
        if (Storage::disk('local')->missing('excel/createdfile/' . $fileName . '.xlsx')) {
            Storage::disk('local')->copy('excel/template/template.xlsx', 'excel/createdfile/' . $fileName . '.xlsx');
        }

        $excelFilePath = Storage::disk('local')->path('excel/createdfile/' . $fileName . '.xlsx');

        $reader = new Rxlsx();
        $spreadsheet = $reader->load($excelFilePath);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'うわーーーーーーーー');
        $sheet->setCellValue('A2', 'うわーーーーーーーー');
        $sheet->setCellValue('A3', 'うわーーーーーーーー');
        $sheet->setCellValue('A4', 'うわーーーーーーーー');
        $sheet->setCellValue('A5', 'うわーーーーーーーー');
        $sheet->setCellValue('A6', 'うわーーーーーーーー');
        $sheetName=$spreadsheet->getSheetByName('sheet');
        $sheetName->setTitle($sheetTitle);



        //保存
        $writer = new Wxlsx($spreadsheet);
        $writer->save($fileName . '.xlsx');
        // 作成したエクセルファイルのパスを返す

        print Storage::disk('public')->path($fileName . '.xlsx');
    }
}

class Configure
{

    function __construct($filepath)
    {
        $this->filePath = $filepath;
        $this->value = $this->configToArray();
        $this->hostname = $this->getHostName();

        $this->baseSetting = []; //基本設定
        $this->interfaceSetting = []; //インターフェース設定
        $this->otherSetting = []; //その他の設定

        //実際の解析
        $this->getHostName();
        $this->createInterfaceSettingArray();
        print_r($this->value);
    }
    private function configToArray()
    {
        $file = fopen($this->filePath, "r");
        $array = [];
        $key = '';

        if ($file) {
            while ($line = fgets($file)) {

                if (preg_match('/^[^\s!]/', rtrim($line), $match) && !strpos($line, '#') !== false) { //文字の先頭に空白がないなら
                    $key = trim($line);
                    $array[$key][] = '';
                }
                if (preg_match('/^\s/', rtrim($line), $match)) { //文字の先頭に空白があるなら
                    $array[$key][] = trim($line);
                }
            }
        }
        return $array;
    }
    private function getHostName()
    {
        foreach ($this->value as $key => $value) {
            if (preg_match('/hostname\s(.*)/', $key, $match)) {
                unset($this->value[$key]);
                return $match[1];
                break;
            }
        }
    }
    private function createBaseSettingArray()
    {
    }
    private function createInterfaceSettingArray()
    {
        foreach ($this->value as $key => $value) {
            if (preg_match('/^interface\s(.*?[a-z])(\d+.*)/', $key, $match)) {
                $interfaceName = $match[1];
                $interfaceId = $match[2];
                unset($value[0]);
                $value = array_values($value);
                foreach ($value as $key2 => $val) {
                    if (gettype($val) == 'string') {
                        if (preg_match('/description\s(.*)/', $val, $match)) {
                            $value['description'] = $match[1];
                            unset($value[$key2]);
                        } else if (preg_match('/ip address\s(.*)/', $val, $match)) {
                            $value['ip address'] = $match[1];
                            unset($value[$key2]);
                        } else if (preg_match('/no\s(.*)/', $val, $match)) {
                            $value['no'][] = $match[1];
                            unset($value[$key2]);
                        } else if (preg_match('/standby\s(.*)/', $val, $match)) {
                            $value['standby'][] = $match[1];
                            unset($value[$key2]);
                        } else if (preg_match('/delay\s(.*)/', $val, $match)) {
                            $value['delay'] = $match[1];
                            unset($value[$key2]);
                        } else if (preg_match('/switchport\s(.*)/', $val, $match)) {
                            $value['switchport'] = $match[1];
                            unset($value[$key2]);
                        } else if (preg_match('/duplex\s(.*)/', $val, $match)) {
                            $value['duplex'] = $match[1];
                            unset($value[$key2]);
                        } else if (preg_match('/speed\s(.*)/', $val, $match)) {
                            $value['speed'] = $match[1];
                            unset($value[$key2]);
                        } else if (preg_match('/encapsulation\s(.*)/', $val, $match)) {
                            $value['encapsulation'] = $match[1];
                            unset($value[$key2]);
                        }
                    }
                }
                // print_r($value);
                $this->interfaceSetting[$interfaceName][$interfaceId] = $value;
                unset($this->value[$key]);
            }
        }
    }
}
