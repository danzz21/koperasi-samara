<?php

namespace App\Controllers;

class Pin extends BaseController
{
    public function create()
    {
        $id_user = session()->get('id');
        
        $validation = \Config\Services::validation();
        $validation->setRules([
            'new_pin' => 'required|numeric|exact_length[6]',
            'confirm_pin' => 'required|matches[new_pin]'
        ]);
        
        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => implode(', ', $validation->getErrors())
            ]);
        }
        
        $new_pin = $this->request->getPost('new_pin');
        
        $db = \Config\Database::connect();
        
        try {
            // Hash PIN sebelum disimpan
            $pin_hash = password_hash($new_pin, PASSWORD_DEFAULT);
            
            // Cek apakah sudah ada PIN
            $existing = $db->table('user_pin')
                ->where('user_id', $id_user)
                ->get()
                ->getRowArray();
            
            if ($existing) {
                // Update PIN
                $db->table('user_pin')
                    ->where('user_id', $id_user)
                    ->update(['pin_hash' => $pin_hash]);
            } else {
                // Insert PIN baru
                $db->table('user_pin')->insert([
                    'user_id' => $id_user,
                    'pin_hash' => $pin_hash,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'PIN berhasil dibuat'
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}