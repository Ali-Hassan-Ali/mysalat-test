<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderStatus;
use App\Models\Order;
use App\Models\Categorey;
use Illuminate\Http\Request;
use App\Traits\TraitFirebase;
use Yajra\DataTables\DataTables;
use Barryvdh\DomPDF\Facade\PDF;

class OrderController extends Controller
{
    use TraitFirebase;

    public function __construct()
    {
        $this->middleware('permission:orders_read')->only(['index','data']);
        $this->middleware('permission:orders_create')->only(['create', 'store']);
        $this->middleware('permission:orders_update')->only(['edit', 'update']);
        $this->middleware('permission:orders_delete')->only(['delete', 'bulk_delete']);

    }// end of __construct

    public function index()
    {
        $categoreys = Categorey::all();

        return view('dashboard.admin.orders.index', compact('categoreys'));

    }//end of index

    public function data()
    {
        $orders = Order::query();

        return DataTables::of($orders)
            ->addColumn('record_select', 'dashboard.admin.orders.data_table.record_select')
            ->editColumn('created_at', function (Order $order) {
                return $order->created_at->format('Y-m-d');
            })
            ->addColumn('user', function (Order $order) {
                return $order->user->username;
            })
            ->editColumn('image', function (Order $order) {
                return view('dashboard.admin.orders.data_table.image', compact('order'));
            })
            ->addColumn('actions', 'dashboard.admin.orders.data_table.actions')
            ->rawColumns(['record_select', 'actions'])
            ->addIndexColumn()
            ->toJson();

    }// end of data


    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        $orderStatus = OrderStatus::all();

        return view('dashboard_admin.orders.edit', compact('order', 'orderStatus'));

    }//end of edit

    
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'order_statuses_id' => ['required','numeric'],
        ]);

        if ($request->order_statuses_id == '1') {

            $this->Send($request, $order);
            
        }//end of if

        $order->update(['order_statuses_id' => $request->order_statuses_id]);

        session()->flash('success', __('dashboard.updated_successfully'));
        return redirect()->route('dashboard.admin.orders.index');


    }//end of update

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    public function invoice(Order $order)
    {
        return view('dashboard.admin.orders.invoice', compact('order'));

    }//end of fun

    public function invoiceStore(Order $order)
    {
        $text = 'السلام عليكم';

        $pdf = \App::make('dompdf.wrapper');

        $pdf->loadHTML('<h1>السلام عليكم</h1>');

        return $pdf->stream();

        $pdf = PDF::loadHTML('<?xml encoding="UTF-8">' .$text, 'UTF-8')->save('myfile.pdf');
        dd($pdf->stream(), $pdf);

        return Pdf::loadFile(public_path().'/myfile.html')
                    ->save('/path-to/my_stored_file.pdf')
                    ->stream('download.pdf');

        $pdf = PDF::loadView('dashboard.admin.orders.invoice', compact('order'));
        return $pdf->download('invoice.pdf');

    }//end of fun

}//end of controller
