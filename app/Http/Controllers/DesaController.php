<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Indonesia;

class DesaController extends Controller
{
    public function index()
    {
        try {
            // $desa = Indonesia::allVillages();
            $desa = Indonesia::allVillages()->slice(0, 100);
            return response()->json($desa, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat mengambil data desa',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    // Get desa by ID
    public function show($villageId)
    {
        try {
            $desa = Indonesia::findVillage($villageId, $with = null);

            if (!$desa) {
                return response()->json([
                    'error' => 'Desa tidak ditemukan'
                ], 404);
            }

            return response()->json($desa, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat mencari desa',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    // Create Desa
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'district_code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'meta' => 'required|array',
            'meta.lat' => 'required|numeric',
            'meta.long' => 'required|numeric',
            'meta.pos' => 'required|string|max:10',
        ]);

        try {
            $desa = Indonesia::createVillage($request->all());
            return response()->json($desa, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menyimpan data desa',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    // Update Desa
    public function update(Request $request, $villageId)
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'district_code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'meta' => 'required|array',
            'meta.lat' => 'required|numeric',
            'meta.long' => 'required|numeric',
            'meta.pos' => 'required|string|max:10',
        ]);

        try {
            $updatedVillage = Indonesia::updateDesa($request->all(), $villageId);

            if (!$updatedVillage) {
                return response()->json([
                    'error' => 'Desa tidak ditemukan'
                ], 404);
            }

            return response()->json($updatedVillage, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal memperbarui data desa',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function destroy($villageId)
    {
        try {
            $response = Indonesia::deleteDesa($villageId);

            if (!$response) {
                return response()->json([
                    'error' => 'Desa tidak ditemukan atau gagal dihapus'
                ], 404);
            }

            return response()->json([
                'message' => 'Desa berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat menghapus desa',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
