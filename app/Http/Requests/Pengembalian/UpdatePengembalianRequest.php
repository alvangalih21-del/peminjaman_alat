<?php 
 
namespace App\Http\Requests\Pengembalian; 
 
use Illuminate\Foundation\Http\FormRequest; 
 
class UpdatePengembalianRequest extends FormRequest 
{ 
    public function authorize(): bool 
    { 
        return true; 
    } 
 
    public function rules(): array 
    { 
        return [ 
            'tgl_kembali' => ['nullable', 'date'],
            'kondisi_kembali' => ['required', 'string', 'max:255'], 
            'denda' => ['nullable', 'integer', 'min:0'], 
        ]; 
    } 
 
 
 
    public function attributes(): array 
    { 
        return [ 
            'tgl_kembali' => 'Tanggal kembali',
            'kondisi_kembali' => 'Kondisi barang kembali', 
            'denda' => 'Nilai denda', 
        ];
    }
}