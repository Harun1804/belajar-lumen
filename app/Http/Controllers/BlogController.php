<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function __construct()
    {
        //
    }

    /**
    * @OA\Get(
    *   path="/blogs",
    *   tags={"Blogs"},
    *   summary="Get All Blogs",
    *   description="Get All Blogs",
    *   @OA\Response(
    *     response=200,
    *     description="success",
    *     @OA\JsonContent(
    *       @OA\Property(property="status", type="string",example="Success"),
    *       @OA\Property(property="message", type="string",example="Blogs Berhasil Didapatkan"),
    *       @OA\Property(property="data", type="array",
    *         @OA\Items(type="object",
    *           @OA\Property(property="id", type="integer",example="1"),
    *           @OA\Property(property="title", type="string",example="Moona Hoshinova"),
    *           @OA\Property(property="content", type="string",example="Salah Satu Vtuber Hololive"),
    *           @OA\Property(property="slug", type="string",example="moona-hoshinova"),
    *           @OA\Property(property="created_at", type="string",example="2022-01-01 00:00:00"),
    *           @OA\Property(property="updated_at", type="string",example="2022-01-01 00:00:00"),
    *         )
    *       )
    *     )
    *   ),
    *   @OA\Response(
    *     response=404,
    *     description="Not Found",
    *     @OA\JsonContent(
    *       @OA\Property(property="message", type="string",example="No blogs found"),
    *     )
    *   )
    * )
    */
    public function index()
    {
        $blogs = DB::table('blogs')->get();
        if($blogs->count() > 0){
            return response()->json([
                'status'    => 'success',
                'message'   => 'Blog berhasil diambil',
                'data'      => $blogs
            ],200);
        }else{
            return response()->json([
                'message' => 'No blogs found'
            ], 404);
        }
    }

    /**
     * @OA\Post(
     *  path="/blogs",
     *  tags={"Blogs"},
     *  summary="Create A Blog",
     *  description="Create A Blog",
     *  @OA\RequestBody(
     *    required=true,
     *    @OA\MediaType(
     *     mediaType="application/x-www-form-urlencoded",
     *     @OA\Schema(
     *      type="object",
     *      @OA\Property(property="title", type="string",example="Moona Hoshinova"),
     *      @OA\Property(property="content", type="string",example="Salah Satu Vtuber Hololive"),
     *     ),
     *    ),
     *  ),
    *   @OA\Response(
    *     response=200,
    *     description="success",
    *     @OA\JsonContent(
    *       @OA\Property(property="status", type="string",example="success"),
    *       @OA\Property(property="message", type="string",example="Blog Berhasil Ditambahkan"),
    *       @OA\Property(property="data", type="array",
    *         @OA\Items(type="object",
    *           @OA\Property(property="id", type="integer",example="1"),
    *           @OA\Property(property="title", type="string",example="Moona Hoshinova"),
    *           @OA\Property(property="content", type="string",example="Salah Satu Vtuber Hololive"),
    *           @OA\Property(property="slug", type="string",example="moona-hoshinova"),
    *           @OA\Property(property="created_at", type="string",example="2022-01-01 00:00:00"),
    *           @OA\Property(property="updated_at", type="string",example="2022-01-01 00:00:00"),
    *         )
    *       )
    *     )
    *   ),
    *   @OA\Response(
    *     response=422,
    *     description="Unprocessable Entity",
    *     @OA\JsonContent(
    *       @OA\Property(property="status", type="string",example="error"),
    *       @OA\Property(property="message", type="string",example="Error Validasi")
    *     )
    *   )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title'     => 'required|string|max:255',
            'content'   => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'    => 'error',
                'message'   => $validator->errors(),
                'data'      => null
            ], 422);
        }else{
            $blogs = Blog::create([
                'title'     => $request->title,
                'slug'      => Str::slug($request->title),
                'content'   => $request->content,
                'user_id'   => Auth::id()
            ]);

            return response()->json([
                'status'    => 'success',
                'message'   => 'Blog berhasil ditambahkan',
                'data'      => $blogs
            ], 200);
        }
    }

    /**
    * @OA\Get(
    *   path="/blogs/{id}",
    *   tags={"Blogs"},
    *   summary="Get A Blogs",
    *   description="Get A Blogs",
    *   @OA\Parameter(
    *     name="id",
    *     in="path",
    *     description="Blog ID",
    *     required=true
    *   ),
    *   @OA\Response(
    *     response=200,
    *     description="success",
    *     @OA\JsonContent(
    *       @OA\Property(property="status", type="string",example="Success"),
    *       @OA\Property(property="message", type="string",example="Blogs Berhasil Diambil"),
    *       @OA\Property(property="data", type="array",
    *         @OA\Items(type="object",
    *           @OA\Property(property="id", type="integer",example="1"),
    *           @OA\Property(property="title", type="string",example="Moona Hoshinova"),
    *           @OA\Property(property="content", type="string",example="Salah Satu Vtuber Hololive"),
    *           @OA\Property(property="slug", type="string",example="moona-hoshinova"),
    *           @OA\Property(property="created_at", type="string",example="2022-01-01 00:00:00"),
    *           @OA\Property(property="updated_at", type="string",example="2022-01-01 00:00:00"),
    *         )
    *       )
    *     )
    *   ),
    *   @OA\Response(
    *     response=404,
    *     description="Not Found",
    *     @OA\JsonContent(
    *       @OA\Property(property="message", type="string",example="No blogs found"),
    *     )
    *   )
    * )
    */
    public function show($id)
    {
        $blogs = Blog::with('user')->find($id);
        if($blogs){
            return response()->json([
                'status'    => 'success',
                'message'   => 'Blog berhasil diambil',
                'data'      => $blogs
            ],200);
        }else{
            return response()->json([
                'message' => 'Blog tidak ditemukan'
            ], 404);
        }
    }

    /**
    * @OA\Put(
    *   path="/blogs/{id}/update",
    *   tags={"Blogs"},
    *   summary="Update a Blog",
    *   description="update A Blog",
    *   @OA\Parameter(
    *     name="id",
    *     in="path",
    *     description="Blog ID",
    *     required=true
    *   ),
    *  @OA\RequestBody(
    *    required=true,
    *    @OA\MediaType(
    *     mediaType="application/x-www-form-urlencoded",
    *     @OA\Schema(
    *      type="object",
    *      @OA\Property(property="title", type="string",example="Moona Hoshinova"),
    *      @OA\Property(property="content", type="string",example="Salah Satu Vtuber Hololive"),
    *     ),
    *    ),
    *  ),
    *   @OA\Response(
    *     response=200,
    *     description="success",
    *     @OA\JsonContent(
    *       @OA\Property(property="status", type="string",example="Success"),
    *       @OA\Property(property="message", type="string",example="Blogs Berhasil Diubah"),
    *       @OA\Property(property="data", type="array",
    *         @OA\Items(type="object",
    *           @OA\Property(property="id", type="integer",example="1"),
    *           @OA\Property(property="title", type="string",example="Moona Hoshinova"),
    *           @OA\Property(property="content", type="string",example="Salah Satu Vtuber Hololive"),
    *           @OA\Property(property="slug", type="string",example="moona-hoshinova"),
    *           @OA\Property(property="created_at", type="string",example="2022-01-01 00:00:00"),
    *           @OA\Property(property="updated_at", type="string",example="2022-01-01 00:00:00"),
    *         )
    *       )
    *     )
    *   ),
    *   @OA\Response(
    *     response=422,
    *     description="Unprocessable Entity",
    *     @OA\JsonContent(
    *       @OA\Property(property="status", type="string",example="error"),
    *       @OA\Property(property="message", type="string",example="Error Validasi")
    *     )
    *   )
    * )
    */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'title'     => 'required|string|max:255',
            'content'   => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'    => 'error',
                'message'   => $validator->errors(),
                'data'      => null
            ], 422);
        }else{
            $blogs = Blog::find($id);
            $blogs->update([
                'title'     => $request->title,
                'slug'      => Str::slug($request->title),
                'content'   => $request->content,
            ]);
        }

        return response()->json([
            'status'    => 'success',
            'message'   => 'Blog berhasil diubah',
            'data'      => $blogs
        ], 200);
    }

    /**
    * @OA\Delete(
    *   path="/blogs/{id}/delete",
    *   tags={"Blogs"},
    *   summary="Delete a Blog",
    *   description="Delete A Blog",
    *   @OA\Parameter(
    *     name="id",
    *     in="path",
    *     description="Blog ID",
    *     required=true
    *   ),
    *   @OA\Response(
    *     response=200,
    *     description="success",
    *     @OA\JsonContent(
    *       @OA\Property(property="status", type="string",example="Success"),
    *       @OA\Property(property="message", type="string",example="Blogs Berhasil Dihapus"),
    *       @OA\Property(property="data", type="array",
    *         @OA\Items(type="object",
    *           @OA\Property(property="id", type="integer",example="1"),
    *           @OA\Property(property="title", type="string",example="Moona Hoshinova"),
    *           @OA\Property(property="content", type="string",example="Salah Satu Vtuber Hololive"),
    *           @OA\Property(property="slug", type="string",example="moona-hoshinova"),
    *           @OA\Property(property="created_at", type="string",example="2022-01-01 00:00:00"),
    *           @OA\Property(property="updated_at", type="string",example="2022-01-01 00:00:00"),
    *         )
    *       )
    *     )
    *   ),
    *   @OA\Response(
    *     response=404,
    *     description="Not Found",
    *     @OA\JsonContent(
    *       @OA\Property(property="message", type="string",example="Blog Tidak Ditemukan"),
    *     )
    *   )
    * )
    */
    public function destroy($id)
    {
        $blogs = Blog::find($id);
        if($blogs){
            $blogs->delete();

            return response()->json([
                'status'    => 'success',
                'message'   => 'Blog berhasil dihapus',
                'data'      => $blogs
            ], 200);
        }else{
            return response()->json([
                'status'  => 'failed',
                'message' => 'Blog tidak ditemukan',
                'data'    => null
            ], 404);
        }

    }
}
