<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Test;
// use App\Models\STOCK_MST;
use App\Models\PRODUCT_BOOKING_old;
// use App\Models\POS_PRODUCTS;
// use App\Models\Pos_Products;
use App\Models\Stock;
use App\Models\ProductBooking;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $STOCK_MST = Stock::with('product')->where('quantity', '>', 0)
        ->get();
        return ['STOCK_MST' => $STOCK_MST];
        // return response()->json("Rabbi");
        // return "hfjha";
        // $user = User::where('id', 1)->first();
        // // $user = new User;
        // $user->name = "sofen2";
        // $user->email = "sofen2@gmail.com";
        // $user->password = "12345";
        // $user->update();

        // return $user;
        // return "testpro";
        // $cnx = DB::connection('oracle');
        // $outs = DB::select("SELECT * FROM hr.hr_user");
        // $user = Auth::user();
        // if($user->is_check == 1){
            
        // }else{
        //     return ['data' => 'At first change password'];
        // }
        // $STOCK_MST = Stock_Mst::with('product')->get();
        // $EMP_PRODUCT_BOOKING = Product_Booking::with('product')->get();
        // $POS_PRODUCTS = Pos_Products::all();
        // $outs1 = DB::select("SELECT * FROM hr.STOCK_MST");
        // $outs2 = DB::select("SELECT * FROM hr.EMP_PRODUCT_BOOKING");
        // $outs3 = DB::select("SELECT * FROM hr.POS_PRODUCTS");
        // return ['STOCK_MST' => $STOCK_MST];
        // return ['STOCK_MST' => $STOCK_MST, 'EMP_PRODUCT_BOOKING' => $EMP_PRODUCT_BOOKING, 'POS_PRODUCTS' => $POS_PRODUCTS];
        // return $outs[0]->title;

        // return $data = DB::table("select * from result");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        return $request;
        $pos = new Product();
        $pos->code = $request->code;
        $pos->created = $request->created;
        $pos->created_by = $request->created_by;
        $pos->description = $request->description;
        $pos->ele_mot_cell = $request->ele_mot_cell;
        $pos->lft = $request->lft;
        $pos->model = $request->model;
        $pos->product_id = $request->product_id;
        $pos->unit_meas_lookup_code = $request->unit_meas_lookup_code;
        $pos->item_model = $request->item_model;
        $pos->product_source_id = $request->product_source_id;
        $pos->segment1 = $request->segment1;
        $pos->segment2 = $request->segment2;
        $pos->segment3 = $request->segment3;
        $pos->segment4 = $request->segment4;
        $pos->item_code = $request->item_code;
        $pos->is_sl = $request->is_sl;
        $pos->save();
        return $pos;

        return $request;
        $stock = new Stock();
        $stock->created = $request->created;
        $stock->grade = $request->grade;
        $stock->hr_cr_emp_entry_by_id = $request->hr_cr_emp_entry_by_id;
        $stock->hr_cr_emp_update_by_id = $request->hr_cr_emp_update_by_id;
        $stock->modified = $request->modified;
        $stock->quantity = $request->quantity;
        $stock->booking_qty = 0;
        // $stock->booking_qty = $request->booking_qty;
        $stock->type = $request->type;
        $stock->product_id = $request->product_id;
        $stock->latest_price = $request->latest_price;
        $stock->save();
        return $stock;

        // return ['stock'=>$stock,'pos'=>$pos];

        // $pos->id = 111;
        // $pos->code = $request->code;
        // $pos->code = "FDL-304";
        // $pos->save();
        // return $pos;
        // $pos->created = $request->created;
        // $pos->created_by = $request->created_by;
        // $pos->description = $request->description;
        // $pos->ele_mot_cell = $request->ele_mot_cell;
        // $pos->lft = $request->lft;
        // $pos->model = $request->model;
        // $pos->modified = $request->modified;
        // $pos->modified_by = $request->modified_by;
        // $pos->product_id = $request->product_id;
        // $pos->unit_meas_lookup_code = $request->unit_meas_lookup_code;
        // $pos->item_model = $request->item_model;
        // $pos->product_source_id = $request->product_source_id;
        // $pos->segment2_ori = $request->segment2_ori;
        // $pos->segment1 = $request->segment1;
        // $pos->segment2 = $request->segment2;
        // $pos->segment3 = $request->segment3;
        // $pos->segment4 = $request->segment4;
        // $pos->segment5 = $request->segment5;
        // $pos->segment2ori = $request->segment2ori;
        // $pos->volume_uom_code = $request->volume_uom_code;
        // $pos->unit_volume = $request->unit_volume;
        // $pos->product_sources_id = $request->product_sources_id;
        // $pos->is_active = $request->is_active;
        // $pos->item_code = $request->item_code;
        // $pos->sub_inv_cat_id = $request->sub_inv_cat_id;
        // $pos->attribute1 = $request->attribute1;
        // $pos->attribute2 = $request->attribute2;
        // $pos->attribute3 = $request->attribute3;
        // $pos->attribute4 = $request->attribute4;
        // $pos->attribute5 = $request->attribute5;
        // $pos->attribute6 = $request->attribute6;
        // $pos->attribute7 = $request->attribute7;
        // $pos->attribute8 = $request->attribute8;
        // $pos->attribute9 = $request->attribute9;
        // $pos->attribute10 = $request->attribute10;
        // $pos->lower_limita = $request->lower_limita;
        // $pos->lower_limitb = $request->lower_limitb;
        // $pos->lower_limitc = $request->lower_limitc;
        // $pos->upper_limita = $request->upper_limita;
        // $pos->upper_limitb = $request->upper_limitb;
        // $pos->upper_limitc = $request->upper_limitc;
        // $pos->vat = $request->vat;
        // $pos->is_sl = $request->is_sl;
        // $pos->inv_item_type_id = $request->inv_item_type_id;
        // $pos->dp_pct = $request->dp_pct;
        // $pos->install_no = $request->install_no;
        // $pos->save();
        // return $pos;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // return $request;
        $stock = Stock::find($id);
        $stock->created = $request->created;
        $stock->grade = $request->grade;
        $stock->hr_cr_emp_entry_by_id = $request->hr_cr_emp_entry_by_id;
        $stock->hr_cr_emp_update_by_id = $request->hr_cr_emp_update_by_id;
        $stock->modified = $request->modified;
        $stock->quantity = $request->quantity;
        $stock->booking_qty = $request->booking_qty;
        $stock->type = $request->type;
        $stock->product_id = $request->product_id;
        $stock->latest_price = $request->latest_price;
        $stock->save();
        // return $stock;
        $pos = Product::find($id);
        // $pos->code = $request->code;
        $pos->created = $request->created;
        $pos->created_by = $request->created_by;
        $pos->description = $request->description;
        $pos->ele_mot_cell = $request->ele_mot_cell;
        $pos->lft = $request->lft;
        $pos->model = $request->model;
        $pos->product_id = $request->product_id;
        $pos->unit_meas_lookup_code = $request->unit_meas_lookup_code;
        $pos->item_model = $request->item_model;
        $pos->product_source_id = $request->product_source_id;
        $pos->segment1 = $request->segment1;
        $pos->segment2 = $request->segment2;
        $pos->segment3 = $request->segment3;
        $pos->segment4 = $request->segment4;
        $pos->item_code = $request->item_code;
        $pos->is_sl = $request->is_sl;
        $pos->update();
        // return $pos;
        return ['stock' => $stock, 'pos' => $pos];
    }

    public function bulkUpload(Request $request)
    {
        $user = Auth::user();
        $filename = $request->file('csvfile');
        if($_FILES["csvfile"]["size"] > 0)
        {
            $file = fopen($filename, "r");
            $i = 0; 
            $insertData = [];     
            $check = 0;         
            while (($item_info = fgetcsv($file, 10000, ",")) !== FALSE)
            {
                if ($i>0) {   
                    try {
                        // return $item_info[0];
                        $productInfo = Product::where('item_model', $item_info[0])->first();
                        if (!empty($productInfo)) {
                            $stockInfo = Stock::where('product_id', $productInfo->id)->where('type', $productInfo->type)->where('grade', $productInfo->grade)->first();
                            if (empty($stockInfo)) {
                                $check = 1;
                                $info['created'] = Carbon::now();
                                $info['grade'] = $item_info[3];
                                $info['hr_cr_emp_entry_by_id'] = $user->eid;
                                $info['modified'] = $item_info[0].' - '.$item_info[1];
                                $info['quantity'] = $item_info[4];
                                $info['type'] = $item_info[2];
                                $info['product_id'] = $productInfo->id;
                                $info['latest_price'] = $item_info[5];
                                $insertData []= $info;
                            }else{
                                $stockInfo->created   = Carbon::now();
                                $stockInfo->grade   = $item_info[3];
                                $stockInfo->hr_cr_emp_entry_by_id   = $user->eid;
                                $stockInfo->modified   = $item_info[0].' - '.$item_info[1];
                                $stockInfo->quantity   += $item_info[4];
                                $stockInfo->type   = $item_info[2];
                                $stockInfo->product_id   = $productInfo->id;
                                $stockInfo->latest_price   = $item_info[5];
                                $stockInfo->update(); 
                            }                              
                        }
                    } catch (\Throwable $th) {
                        return $th->getMessage();
                    }                 
                }
                $i++;
            }
        }
        try {
            if ($check == 1) {
                Stock::insert($insertData);
                return "Uploaded";
            }else{
                return "Updated";
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
        return "Product stock uploaded";
    }

    public function productUpload(Request $request)
    {
        $user = Auth::user();
        // return $user->eid;
        $filename = $request->file('csvfile');
        if($_FILES["csvfile"]["size"] > 0)
        {
            $file = fopen($filename, "r");
            $i = 0;               
            while (($item_info = fgetcsv($file, 10000, ",")) !== FALSE)
            {
                if ($i>0) {   
                    try {
                        // $productInfo = Pos_Products::where('item_model', $item_info[1])->first();
                        // if (!empty($productInfo)) {
                        //     // $productInfo = new Pos_Products;
                        //     $productInfo->code = $item_info[0].$item_info[1].$item_info[2];
                        //     $productInfo->created = Carbon::now();
                        //     $productInfo->created_by = $user->eid;
                        //     $productInfo->description = $item_info[1];
                        //     $productInfo->lft = 1;
                        //     $productInfo->model = $item_info[1];
                        //     $productInfo->unit_meas_lookup_code = 'PCS';
                        //     $productInfo->item_model = $item_info[2];
                        //     $productInfo->segment1 = 'SAMSUNG';
                        //     $productInfo->segment2 = 'MOBILE';
                        //     $productInfo->segment3 = 'SMART PHONE';
                        //     $productInfo->segment4 = 'LOCAL';
                        //     $productInfo->is_sl = 1;
                        //     $productInfo->update();
                        // }else{
                            $productInfo = new Product;
                            $productInfo->code = $item_info[0].$item_info[1].$item_info[2];
                            $productInfo->created = Carbon::now();
                            $productInfo->created_by = $user->eid;
                            $productInfo->description = $item_info[1].'-'.$item_info[2];
                            $productInfo->lft = 1;
                            $productInfo->model = $item_info[2];
                            $productInfo->unit_meas_lookup_code = 'PCS';
                            $productInfo->item_model = $item_info[1];
                            $productInfo->segment1 = 'SAMSUNG';
                            $productInfo->segment2 = 'MOBILE';
                            $productInfo->segment3 = 'SMART PHONE';
                            $productInfo->segment4 = 'LOCAL';
                            $productInfo->is_sl = 1;
                            $productInfo->save();
                        // }
                    } catch (\Throwable $th) {
                        return $th->getMessage();
                    }                        
                }
                $i++;
            }
        }
        return "product uploaded";
    }

    public function userUpload(Request $request)
    {
        // return 'hhhh';
        $user = Auth::user();
        $filename = $request->file('csvfile');
        if($_FILES["csvfile"]["size"] > 0)
        {
            $file = fopen($filename, "r");
            $insertData = [];
            $check = 0;
            $i = 0;               
            while (($item_info = fgetcsv($file, 10000, ",")) !== FALSE)
            {
                if ($i>0) {   
                    try {
                        // return $item_info[2];
                        $userInfo = User::where('eid', $item_info[3])->first();
                        // if (!empty($userInfo)) {
                        //     // $productInfo = new Pos_Products;
                        //     $userInfo->name = $item_info[1];
                        //     $userInfo->oldpass = $item_info[2];
                        //     $userInfo->eid = $item_info[3];
                        //     $userInfo->is_check = $item_info[4];
                        //     $userInfo->update();
                        // }else{
                            // $userInfo = new User;
                            // $userInfo->name = $item_info[1];
                            // $userInfo->oldpass = $item_info[2];
                            // $userInfo->eid = $item_info[3];
                            // $userInfo->is_check = $item_info[4];
                            // $userInfo->save();
                            
                            $check = 1;
                            $info['created_at'] = Carbon::now();
                            $info['name'] = $item_info[1];
                            $info['oldpass'] = $item_info[2];
                            $info['eid'] = $item_info[3];
                            // $info['is_check'] = $item_info[4];
                            $insertData []= $info;
                        // }
                    } catch (\Throwable $th) {
                        return $th->getMessage();
                    }                        
                }
                $i++;
            }
        }

        try {
            if ($check == 1) {
                User::insert($insertData);
                return "Uploaded";
            }else{
                return "Updated";
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
        return "users uploaded";
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
