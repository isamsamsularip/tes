<?php
namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

class TransaksiController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->user
            ->transaksi()
            ->where('flag', 0)
            ->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate data
        $data = $request->only('tanggal','category','keterangan','pemasukan','pengeluaran');
        $validator = Validator::make($data, [
            'tanggal'       => 'required|string',
            'category'      => 'required|string',
            'keterangan'    => 'required|string',
            'pemasukan'     => 'required|string',
            'pengeluaran'   => 'required|string'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new category
        $transaksi = $this->user->transaksi()->create([
            'tanggal'       => $request->tanggal,
            'category'      => $request->category,
            'keterangan'    => $request->keterangan,
            'pemasukan'     => $request->pemasukan,
            'pengeluaran'   => $request->pengeluaran,
            'flag'          => 0
        ]);

        //category created, return success response
        return response()->json([
            'success' => true,
            'message' => 'category created successfully',
            'data' => $transaksi
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaksi = $this->user->transaksi()->find($id);

        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, transaksi not found.'
            ], 400);
        }

        return $transaksi;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function edit(category $transaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        //Validate data
        $data = $request->only('tanggal','category','keterangan','pemasukan','pengeluaran');
        $validator = Validator::make($data, [
            'tanggal'       => 'required|string',
            'category'      => 'required|string',
            'keterangan'    => 'required|string',
            'pemasukan'     => 'required|string',
            'pengeluaran'   => 'required|string'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update category
        $transaksi = $transaksi->update([
            'tanggal'       => $request->tanggal,
            'category'      => $request->category,
            'keterangan'    => $request->keterangan,
            'pemasukan'     => $request->pemasukan,
            'pengeluaran'   => $request->pengeluaran
        ]);

        //category updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'category updated successfully',
            'data' => $transaksi
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaksi $transaksi)
    {
        $transaksi = $transaksi->update([
            'flag'   => 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'transakasi deleted successfully'
        ], Response::HTTP_OK);
    }

    public function GetLaporan(Request $request)
    {
        if($request->limit != NULL or $request->limit > 0)
        {
           $limit = $request->limit;
        }else
        {
            $limit = 1;
        }
        if($request->page != NULL or $request->page > 0)
        {
           $page = $request->page;
        }else
        {
            $page = 1;
        }

        //get data from table posts
        $data =  $this->user
        ->transaksi()
        ->where('flag', 0);

        if($request->start_date != NULL or $request->start_date > 0 && $request->end_date != NULL or $request->end_date > 0)
        {
           $data = $data->where('tanggal', 'BETWEEN',  $request->start_date . 'AND' .  $request->end_date);
        }

        // $data = $data->orderBy('id', 'desc');
        $paginated = $data->paginate($limit, ['*'], 'pg', $page);
        //make response JSON
        return response()->json([
            'code'          => '200',
            'status'        => 'succes',
            'data'          => $paginated
        ]);
    }
}
