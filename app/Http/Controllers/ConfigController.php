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
    function change(Request $request)
    {
        $files = $request->file(); //アップロードされたファイルを変数に代入

        foreach ($files as $file) {

            $ext = $file->getClientOriginalExtension(); //拡張子を取得
            //ファイルのオリジナルファイル名を取得
            $name = basename($file->getClientOriginalName(), "." . $ext);


            //アップロードされたファイルが storage/file になかったら保存
            if (Storage::disk('local')->missing('file/' . $name . "." . $ext)) {
                print "保存";

                //保存  strage/app/file に保存される
                $file->storeAs('file', $name . "." . $ext);
            } else {
                print "保存済み";
            }
            //保存したファイルのフルパスを取得
            $uploadedFilePaths[][$name] = Storage::disk('local')->path('file/' . $name . "." . $ext);
        }

        // 複数のコンフィグファイルを変換して1つのエクセルに変換して、そのファイルのパスを返す
        $excelFilePath = $this->toExcelFile($uploadedFilePaths);

        exec("rm /Users/yokotsukahiroki/work/samurai/lesson/configure/storage/app/file/*");
        ob_end_clean();//これがないとDL後のファイルが破損してしまう  参考：https://www.366service.com/jp/qa/0348ce6a048c7c8c9dbabae1981a3ac3
        return (Storage::disk('local')->download($excelFilePath));
    }
    private function filePathToArray($path)
    {
        $c = new Configure($path);
    }
    
    private function excelTest()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');

        $writer = new Wxlsx($spreadsheet);
        $writer->save('hello world.xlsx');
    }
    private function toExcelFile(array $uploadedFilePaths): string
    {

        // 書き込み用ワークブック
        $spreadsheet = new Spreadsheet();


        foreach ($uploadedFilePaths as $key => $value) {
            
            foreach ($value as $fileName => $filePath) {
                // テンプレートシート関連
                $templeteExcelFilePath = Storage::disk('local')->path('excel/template/template.xlsx');
                $reader = new Rxlsx();
                // テンプレれ読み込み
                $templeteSpreadsheet = $reader->load($templeteExcelFilePath);
                // テンプレートシート関連 終わり
        
                $configure = new Configure($filePath);

                // セル代入用変数
                $columnIndex;
                $row = 86;
                $value;

                $clonedWorksheet = clone $templeteSpreadsheet->getSheetByName('sheet');
                $spreadsheet->addExternalSheet($clonedWorksheet);
                $sheet = $spreadsheet->getSheetByName('sheet');
                $sheet->setTitle($configure->hostname);
                
                // ホスト名
                $sheet->setCellValueByColumnAndRow(1, 3, $configure->hostname);
                foreach ($configure->interfaceSetting as $interfaceName => $array) {

                    foreach ($array as $interfaceId => $array2) {

                        foreach ($array2 as $item => $value) {
                            $sheet->setCellValueByColumnAndRow(1, $row, $interfaceName);
                            $sheet->setCellValueByColumnAndRow(2, $row, $interfaceId);
                            switch ($item) {
                                case 'description':
                                    $sheet->setCellValueByColumnAndRow(3, $row, $value);
                                    break;
                                case 'switchport':
                                    $sheet->setCellValueByColumnAndRow(12, $row, $value);
                                    break;
                                    // case 'encapsulation':
                                    //     $sheet->setCellValueByColumnAndRow(12, $row, $value);
                                    //     break;
                                case 'speed':
                                    $sheet->setCellValueByColumnAndRow(4, $row, $value);
                                    break;
                                case 'duplex':
                                    $sheet->setCellValueByColumnAndRow(5, $row, $value);
                                    break;
                                case 'ip address':
                                    $sheet->setCellValueByColumnAndRow(6, $row, $value);
                                    break;
                            }
                        }
                        $row++;
                    }
                }
            }
        }
        $spreadsheet->removeSheetByIndex(0);

        //保存
        $writer = new Wxlsx($spreadsheet);
        $writer->save('/Users/yokotsukahiroki/work/samurai/lesson/configure/storage/app/excel/createdfile/changedfile.xlsx');
        // 作成したエクセルファイルのパスを返す
        return 'excel/createdfile/changedfile.xlsx';
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
        // print_r($this->value);
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
    private function extractIpAddress(){
        $this->interfaceSetting;
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
