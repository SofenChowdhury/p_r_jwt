<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Classes\Helper;
use App\Classes\FileUpload;
// use Illuminate\Http\Request;
// use App\Models\Product_Booking;
// use App\Models\Pos_Products;
use App\Models\Stock;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPaymentUpload;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\Object_;
use App\Models\ProductBooking;
use Ramsey\Uuid\Type\Integer;
use App\Models\Product;

class ProductBookingController extends Controller
{
    protected $file;
    protected $badge;
    protected $is_fwd;

    public function __construct(FileUpload $fileUpload)
    {
        $this->file = $fileUpload;
        $this->badge = 3;
        $this->is_fwd = 5;
    }

    // List for all users booked order 
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            return ProductBooking::with('stock.product')->where('user_id', $user->id)->get();
        }
        return Response(['data' => 'Unauthorized'], 401);
    }

    // List before purchase product list 
    public function productIndex(){
        if (Auth::check()) {
            $stock = Stock::with('product')
            ->where('quantity', '>', 0)
            // ->where('badge', '=', 2)//for new stock
            ->where('badge', '=', $this->badge)//for new stock
            ->get();
            // return ['data' => $stock];
            $grade = [];  
            $type = Stock::
            select('type')
            ->where('quantity', '>', 0)
            // ->where('badge', '=', 2)//for new stock
            ->where('badge', '=', $this->badge)//for new stock
            ->groupBy('type')
            ->get();
            // return $type;
            // return $totalType = count($type);
            $totalType = count($type);
            for ($i=0; $i < $totalType; $i++) { 
                $grade[$type[$i]->type] = Stock::where('type',$type[$i]->type)
                ->select('grade')
                ->where('quantity', '>', 0)
                // ->where('badge', '=', 2)//for new stock
                ->where('badge', '=', $this->badge)//for new stock
                ->groupBy('grade')
                ->get();
            }
            // return ['type' => $type, 'data' => $stock];
            return ['type' => $type, 'grade' => $grade, 'STOCK_MST' => $stock];
        }
        return Response(['data' => 'Unauthorized'], 401);
    }

    // purchse order 
    public function store(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
        
            DB::beginTransaction();
            try {
                $stock_check = Stock::where('id', $request->stock_id)->lockForUpdate()->first();
        
                // $check_booking = Product_Booking::where('modified', $user->eid)->where('is_fwd', 4)->first();
                $check_booking = ProductBooking::where('modified', $user->eid)->where('is_fwd', $this->is_fwd)->first();
                
                if ($stock_check->quantity > 0) {
        
                    if (empty($check_booking)) {
                        $booking = new ProductBooking();
                        $booking->grade = $request->grade;
                        $booking->user_id = $user->id;
                        $booking->hr_cr_emp_id = $request->hr_cr_emp_id;
                        $booking->hr_cr_emp_entry_by_id = $request->hr_cr_emp_entry_by_id;
                        $booking->hr_cr_emp_update_by_id = $request->hr_cr_emp_update_by_id;
                        // $booking->is_fwd = 4;//previous load track
                        $booking->is_fwd = $this->is_fwd;//previous load track
                        $booking->modified = $user->eid;
                        $booking->quantity = 1;
                        $booking->product_id = $stock_check->id;
                        $booking->type = $request->type;
                        $booking->price = $stock_check->latest_price;
                        $booking->remarks = $request->remarks;
                        $booking->delivery_location = $request->delivery_location;
                        $booking->mob_code = $request->mob_code;
                        $booking->location = $request->location;
                        $booking->p_type = 1;
                        $booking->save();
        
                        $stock_check->quantity -= 1;
                        $stock_check->booking_qty += 1;
                        $stock_check->update();
        
                        DB::commit();
        
                        return [
                            'status' => true,
                            'booking' => $booking,
                            'stock' => $stock_check,
                            'message' => "Your booking has been successfully submitted!"
                        ];
                    } else {
                        return [
                            'status' => false,
                            'booking' => [],
                            'stock' => $stock_check,
                            'message' => "You have already booked 1 item, you can't book multiple items"
                        ];
                    }
                } else {
                    return ['status' => false, 'message' => "Item is out of stock"];
                }
            } catch (\Exception $e) {
                DB::rollback();
                return ['status' => false, 'message' => "An error occurred while processing your request"];
            }
        }
        
        return Response(['data' => 'Unauthorized'], 401);

        // if (Auth::check()) {
        //     $user = Auth::user();
        //     $stock_check = Stock_Mst::where('id', $request->stock_id)->first();
            
        //     if ($stock_check->quantity > 0) {
        //         // $check_booking = Product_Booking::where('modified', $user->eid)->where('type', $stock_check->type)->where('is_fwd', 2)->first();
        //         $check_booking = Product_Booking::where('modified', $user->eid)->where('is_fwd', 3)->first();
        //         if (empty($check_booking)) {
        //             DB::beginTransaction();
        //             try {
        //                 $booking = new Product_Booking();
        //                 $booking->grade = $request->grade;
        //                 $booking->hr_cr_emp_id = $request->hr_cr_emp_id;
        //                 $booking->hr_cr_emp_entry_by_id = $request->hr_cr_emp_entry_by_id;
        //                 $booking->hr_cr_emp_update_by_id = $request->hr_cr_emp_update_by_id;
        //                 $booking->is_fwd = 3;//previous load track
        //                 $booking->modified = $user->eid;
        //                 $booking->quantity = 1;
        //                 $booking->product_id = $stock_check->id;
        //                 $booking->type = $request->type;
        //                 $booking->price = $stock_check->latest_price;
        //                 $booking->remarks = $request->remarks;
        //                 $booking->delivery_location = $request->delivery_location;
        //                 $booking->mob_code = $request->mob_code;
        //                 $booking->location = $request->location;
        //                 $booking->p_type = 1;
        //                 $booking->save();
        //                 $stock_check->quantity -= 1;
        //                 $stock_check->booking_qty += 1;
        //                 $stock_check->update();
        //                 DB::commit();
        //             } catch (\Exception $e) {
        //                 DB::rollback();
        //             }
        //             return ['status' => true, 'booking' => $booking, 'stock' => $stock_check, 'message' => "Your booking has benn successfully sumbited!"];
        //         } else {
        //             return ['status' => false, 'booking' => [], 'stock' => $stock_check, 'message' => "You have already booked 1 item, you can't book multiple items"];
        //         }
        //     }else{
        //         return ['status' => false, 'message' => "Item is out of stock"];
        //     }
        // }
        // return Response(['data' => 'Unauthorized'], 401);
    }

    // Upload payslip
    public function update(Request $request, string $id)
    {
        if (Auth::check()) {
            try {
                $fileName =  $this->file->base64ImgUpload($request->file_url ,$file = "", $folder = "file_url");
    
                $user = Auth::user();
                // $booking = Product_Booking::with('stock.product')->where('id', $id)->where('is_fwd', 4)->first();
                $booking = ProductBooking::with('stock.product')->where('id', $id)->where('is_fwd', $this->is_fwd)->first();
                if ($user->eid == $booking->modified) {
                    if ($booking->is_eligible == 1) {
                        return ['status' =>false, 'data' => [], 'message' => "Your deposited slip already uploaded !"];
                    }else{
                        $booking->created = Carbon::now();
                        $booking->file_url = $fileName;
                        $booking->upload_id = $user->eid;
                        // $booking->p_type = 1;
                        $booking->is_eligible = 1;
                        $booking->update();
                        // Mail::to('employee.deposit@fdl.com.bd')
                        // Mail::to('md.rabby.mahmud@gmail.com')
                        // ->cc('rabby.mahmud@fel.com.bd')
                        // ->send(new OrderPaymentUpload($booking->type, $booking, $user, $booking->file_url));
                        
                        return ['status' =>true, 'data' => $booking, 'message' => "Your deposited slip has been uploaded successfully!"];
                    }
                }else{
                    return ['status' =>false, 'data' => [], 'message' => "You are not a valid user !"];
                }
            } catch (\Throwable $th) {
                return ['status' =>false, 'data' => [], 'message' => "test error"];
                // return ['status' =>false, 'data' => [], 'message' => $th];
            }
        }
        return Response(['data' => 'Unauthorized'], 401);
    }

    // admin status 
    public function productSearch(Request $request){
        // return $request->type;
        if (Auth::check()) {
            if($request->type == ''){
                $stock = Stock::with('order')->where('badge',2)->get();
                // return ['data' => $stock];
                // $grade = [];
                $type = Stock::
                select('type')
                // ->where('badge',2)
                ->where('badge',$this->badge)
                ->groupBy('type')
                ->get();
                // return $type;
                // return $totalType = count($type);
                // $totalType = count($type);
                // for ($i=0; $i < $totalType; $i++) { 
                //     $grade[$type[$i]->type] = Stock_Mst::where('type',$type[$i]->type)->select('grade')
                //     ->groupBy('grade')
                //     ->get();
                // }
                return ['type' => $type, 'data' => $stock];
            }else{
                $stock = Stock::with('order')
                // ->where('badge',2)
                ->where('badge',$this->badge)
                ->where('type','like',$request->type)
                // ->orWhere('grade','like',$request->grade)
                ->get();
                // return ['data' => $stock];
                $type = Stock::
                select('type')
                // ->where('badge',2)
                ->where('badge',$this->badge)
                ->groupBy('type')
                ->get();
                // return $type;
                // return $totalType = count($type);
                // $grade = [];
                // $totalType = count($type);
                // for ($i=0; $i < $totalType; $i++) { 
                //     $grade[$type[$i]->type] = Stock_Mst::where('type',$type[$i]->type)
                //     ->select('grade')
                //     ->groupBy('grade')
                //     ->get();
                // }
                // return $grade['LDU'][0]->grade;
                return ['type' => $type, 'data' => $stock];
                // return ['type' => $type, 'grade' => $grade, 'data' => $stock];
            }
        }
        return Response(['data' => 'Unauthorized'], 401);
    }

    // product requisition Admin
    public function indexAdmin()
    {
        if (Auth::check()) {
            $booking = ProductBooking::with('stock.product','user')
            // ->where('is_fwd', 4)
            ->where('is_fwd', $this->is_fwd)
            ->where('is_eligible', 1)
            ->where('p_type', 1)
            ->orderBy('created', 'asc')
            ->paginate(30);
            return $booking;
        }
        return Response(['data' => 'Unauthorized'], 401);
    }

    // order approved by admin 
    public function storeAdmin(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            for ($i=0; $i < count($request->booking_id); $i++) { 
                $booking = ProductBooking::where('id',$request->booking_id[$i])->first();
                $booking->is_approved = $request->is_approved; // for approved or denay
                $booking->approval_time = Carbon::now();
                $booking->approved_by = $user->eid;

                $booking->location = $request->location;
                $booking->p_type = 0;

                $booking->update();
            }
            return ['message' => 'Checked', 'data' => $booking];
        }
        return Response(['data' => 'Unauthorized'], 401);
    }

    // report download list admin 
    public function downloadReport(){
        if (Auth::check()) {
            // $booking = Product_Booking::with('stock.product','user')->where('p_type', 1)->where('type','EOL')->get();
            $booking = ProductBooking::with('stock.product','user')->where('is_fwd',4)->where('is_eligible',1)->where('p_type', 0)->where('is_approved',1)->get();
            return ['data'=>$booking];
        }
        return Response(['data' => 'Unauthorized'], 401);
    }

    // after report download list Admin 
    public function downloadReportNext(Request $request){
        // return $total = count($request->bookingDownload);
        if (Auth::check()) {
            $total = count($request->bookingDownload);
            if($total > 0){
                for($i=0; $i<$total; $i++){
                    $booking = ProductBooking::where('id',$request->bookingDownload[$i]['id'])->first();
                    
                    $booking->p_type = 2;
                    $booking->update();
                }
                return ['data'=>"downloaded"];
            }else{
                return ['data'=>"No data exist"];
            }
        }
        return Response(['data' => 'Unauthorized'], 401);
    }

     // After approved product list admin 
     public function indexAdminApproved()
     {
         if (Auth::check()) {
             $bookingApproved = ProductBooking::with('stock.product','user')
            //  ->where('is_fwd',4)
             ->where('is_fwd',$this->is_fwd)
             ->where('is_eligible',1)
             ->where('p_type', 2)
             ->where('is_approved',1)
             ->paginate(30);
             $totalBookingApproved = ProductBooking::with('stock.product','user')
            //  ->where('is_fwd',4)
             ->where('is_fwd',$this->is_fwd)
             ->where('is_eligible',1)
             ->where('p_type', 2)
             ->where('is_approved',1)
             ->get()
             ->count();
             $bookingApprovedReport = ProductBooking::with('stock.product','user')
            //  ->where('is_fwd',4)
             ->where('is_fwd',$this->is_fwd)
             ->where('is_eligible',1)
             ->where('p_type', 2)
             ->where('is_approved',1)
             ->paginate(30);
             return ['totalBookingApproved'=>$totalBookingApproved,'bookingApproved'=>$bookingApproved, 'bookingApprovedReport'=>$bookingApprovedReport];
         }
         return Response(['data' => 'Unauthorized'], 401);
     }

    //roleback callback function
    private function destroyId(Object $object)
    {
        $total = count($object);
        for($i=0; $i<$total; $i++){
            // $stock_check = Stock_Mst::where('id', $object[$i]['product_id'])->first();
            // $stock_check = Stock_Mst::where('id', $object[$i]['product_id'])->where('badge','=',2)->first();
            $stock_check = Stock::where('id', $object[$i]['product_id'])->where('badge','=',$this->badge)->first();
            $stock_check->quantity += 1;
            $stock_check->booking_qty -= 1;
            $stock_check->update();
            // $booking = Product_Booking::where('id', $object[$i]['id'])->delete();
            $booking = ProductBooking::where('id', $object[$i]['id'])->first();
            $booking->file_url = 0;
            $booking->p_type = 3;
            $booking->update();
        }
        return ['booking_status'=>1, 'stock_check'=>$stock_check];
        // return ['booking_status'=>$booking, 'stock_check'=>$stock_check];
    }

    // roleback with date 
    public function rolebackOrderDate(Request $request){
        if (Auth::check()) {
            $from = $request->start;
            $to = $request->end;
            $check = $request->rollback;
            $roleback = ProductBooking::whereBetween('created_at', [$from, $to])
            ->with('stock.product','employee')
            // ->where('is_fwd', 4)
            ->where('is_fwd', $this->is_fwd)
            ->where('p_type', 1)
            // ->where('is_eligible','!=', 1)
            // ->where('is_approved','!=', 1)
            ->get();
            if($check == 1){
                return $returnObject = $this->destroyId($roleback);
            }else{
                return $roleback;
            }
        }
        return Response(['data' => 'Unauthorized'], 401);
    }

    // roleback with time
    public function rolebackOrderTime(Request $request){
        if (Auth::check()) {
            $hours = $request->hours;
            $check = $request->rollback;
            $roleback = ProductBooking::where('created_at', '>', Carbon::now()
            ->addHours(-$hours))
            ->with('stock.product','user')
            // ->where('is_fwd', 4)
            ->where('is_fwd', $this->is_fwd)
            ->where('p_type', 1)
            // ->where('is_eligible','!=', 1)
            // ->where('is_approved','!=', 1)
            ->get();
            if($check == 1){
                return $returnObject = $this->destroyId($roleback);
            }else{
                return $roleback;
            }
        }
        return Response(['data' => 'Unauthorized'], 401);
    }

    public function productStatus(){
        if (Auth::check()) {
            $stock = Stock::with('product')
            ->where('quantity', '>', 0)
            // ->where('badge', '=', 2)
            ->where('badge', '=', $this->badge)
            ->get();
            // return ['data' => $stock];
            $grade = [];
            $type = Stock::
            select('type')
            // ->where('quantity', '>', 0)
            ->groupBy('type')
            ->get();
            // return $type;
            // return $totalType = count($type);
            $totalType = count($type);
            for ($i=0; $i < $totalType; $i++) { 
                $grade[$type[$i]->type] = Stock::where('type',$type[$i]->type)->select('grade')
                ->groupBy('grade')
                ->get();
            }
            // return ['type' => $type, 'data' => $stock];
            return ['type' => $type, 'grade' => $grade, 'STOCK_MST' => $stock];
        }
        return Response(['data' => 'Unauthorized'], 401);
    }

    public function roleBackAdmin(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            for ($i=0; $i < count($request->booking_id); $i++) { 
                $booking = ProductBooking::where('id',$request->booking_id[$i])->first();
                $booking->is_approved = $request->is_approved;
                $booking->approval_time = Carbon::now();
                $booking->approved_by = $user->eid;

                $booking->location = $request->location;
                $booking->p_type = 0;

                $booking->update();
            }
            return ['message' => 'Checked', 'data' => $booking];
        }
        return Response(['data' => 'Unauthorized'], 401);
    }

    private function stock(Request $request, string $id)
    {
        return 'stock';
    }

    public function show(string $id)
    {
        return 'show';
    }

    public function destroy(string $id)
    {
        if (Auth::check()) {
            $booking = ProductBooking::where('id', $id)->delete();
            return $booking;
        }
        return Response(['data' => 'Unauthorized'], 401);
    }
}
