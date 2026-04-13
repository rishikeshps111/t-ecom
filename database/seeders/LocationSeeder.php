<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            // Johor (state_id = 1)
            ['code' => '0001', 'name' => 'Johor Bahru', 'state_id' => 1, 'status' => 1],
            ['code' => '0002', 'name' => 'Batu Pahat', 'state_id' => 1, 'status' => 1],
            ['code' => '0003', 'name' => 'Muar', 'state_id' => 1, 'status' => 1],
            ['code' => '0004', 'name' => 'Kluang', 'state_id' => 1, 'status' => 1],
            ['code' => '0005', 'name' => 'Iskandar Puteri', 'state_id' => 1, 'status' => 1],
            ['code' => '0006', 'name' => 'Pasir Gudang', 'state_id' => 1, 'status' => 1],
            ['code' => '0007', 'name' => 'Segamat', 'state_id' => 1, 'status' => 1],

            // Kedah (state_id = 2)
            ['code' => '0008', 'name' => 'Alor Setar', 'state_id' => 2, 'status' => 1],
            ['code' => '0009', 'name' => 'Sungai Petani', 'state_id' => 2, 'status' => 1],
            ['code' => '0010', 'name' => 'Kulim', 'state_id' => 2, 'status' => 1],
            ['code' => '0011', 'name' => 'Langkawi', 'state_id' => 2, 'status' => 1],
            ['code' => '0012', 'name' => 'Baling', 'state_id' => 2, 'status' => 1],

            // Kelantan (state_id = 3)
            ['code' => '0013', 'name' => 'Kota Bharu', 'state_id' => 3, 'status' => 1],
            ['code' => '0014', 'name' => 'Pasir Mas', 'state_id' => 3, 'status' => 1],
            ['code' => '0015', 'name' => 'Tumpat', 'state_id' => 3, 'status' => 1],
            ['code' => '0016', 'name' => 'Tanah Merah', 'state_id' => 3, 'status' => 1],
            ['code' => '0017', 'name' => 'Machang', 'state_id' => 3, 'status' => 1],

            // Melaka (state_id = 4)
            ['code' => '0018', 'name' => 'Melaka City', 'state_id' => 4, 'status' => 1],
            ['code' => '0019', 'name' => 'Alor Gajah', 'state_id' => 4, 'status' => 1],
            ['code' => '0020', 'name' => 'Jasin', 'state_id' => 4, 'status' => 1],

            // Negeri Sembilan (state_id = 5)
            ['code' => '0021', 'name' => 'Seremban', 'state_id' => 5, 'status' => 1],
            ['code' => '0022', 'name' => 'Port Dickson', 'state_id' => 5, 'status' => 1],
            ['code' => '0023', 'name' => 'Nilai', 'state_id' => 5, 'status' => 1],
            ['code' => '0024', 'name' => 'Kuala Pilah', 'state_id' => 5, 'status' => 1],
            ['code' => '0025', 'name' => 'Rembau', 'state_id' => 5, 'status' => 1],

            // Pahang (state_id = 6)
            ['code' => '0026', 'name' => 'Kuantan', 'state_id' => 6, 'status' => 1],
            ['code' => '0027', 'name' => 'Temerloh', 'state_id' => 6, 'status' => 1],
            ['code' => '0028', 'name' => 'Bentong', 'state_id' => 6, 'status' => 1],
            ['code' => '0029', 'name' => 'Raub', 'state_id' => 6, 'status' => 1],
            ['code' => '0030', 'name' => 'Cameron Highlands', 'state_id' => 6, 'status' => 1],
            ['code' => '0031', 'name' => 'Pekan', 'state_id' => 6, 'status' => 1],

            // Perak (state_id = 7)
            ['code' => '0032', 'name' => 'Ipoh', 'state_id' => 7, 'status' => 1],
            ['code' => '0033', 'name' => 'Taiping', 'state_id' => 7, 'status' => 1],
            ['code' => '0034', 'name' => 'Teluk Intan', 'state_id' => 7, 'status' => 1],
            ['code' => '0035', 'name' => 'Kampar', 'state_id' => 7, 'status' => 1],
            ['code' => '0036', 'name' => 'Manjung', 'state_id' => 7, 'status' => 1],
            ['code' => '0037', 'name' => 'Batu Gajah', 'state_id' => 7, 'status' => 1],

            // Perlis (state_id = 8)
            ['code' => '0038', 'name' => 'Kangar', 'state_id' => 8, 'status' => 1],
            ['code' => '0039', 'name' => 'Arau', 'state_id' => 8, 'status' => 1],

            // Penang (state_id = 9)
            ['code' => '0040', 'name' => 'George Town', 'state_id' => 9, 'status' => 1],
            ['code' => '0041', 'name' => 'Butterworth', 'state_id' => 9, 'status' => 1],
            ['code' => '0042', 'name' => 'Bukit Mertajam', 'state_id' => 9, 'status' => 1],
            ['code' => '0043', 'name' => 'Bayan Lepas', 'state_id' => 9, 'status' => 1],

            // Sabah (state_id = 10)
            ['code' => '0044', 'name' => 'Kota Kinabalu', 'state_id' => 10, 'status' => 1],
            ['code' => '0045', 'name' => 'Sandakan', 'state_id' => 10, 'status' => 1],
            ['code' => '0046', 'name' => 'Tawau', 'state_id' => 10, 'status' => 1],
            ['code' => '0047', 'name' => 'Lahad Datu', 'state_id' => 10, 'status' => 1],
            ['code' => '0048', 'name' => 'Keningau', 'state_id' => 10, 'status' => 1],
            ['code' => '0049', 'name' => 'Semporna', 'state_id' => 10, 'status' => 1],

            // Sarawak (state_id = 11)
            ['code' => '0050', 'name' => 'Kuching', 'state_id' => 11, 'status' => 1],
            ['code' => '0051', 'name' => 'Miri', 'state_id' => 11, 'status' => 1],
            ['code' => '0052', 'name' => 'Sibu', 'state_id' => 11, 'status' => 1],
            ['code' => '0053', 'name' => 'Bintulu', 'state_id' => 11, 'status' => 1],
            ['code' => '0054', 'name' => 'Samarahan', 'state_id' => 11, 'status' => 1],
            ['code' => '0055', 'name' => 'Sri Aman', 'state_id' => 11, 'status' => 1],

            // Selangor (state_id = 12)
            ['code' => '0056', 'name' => 'Shah Alam', 'state_id' => 12, 'status' => 1],
            ['code' => '0057', 'name' => 'Petaling Jaya', 'state_id' => 12, 'status' => 1],
            ['code' => '0058', 'name' => 'Subang Jaya', 'state_id' => 12, 'status' => 1],
            ['code' => '0059', 'name' => 'Klang', 'state_id' => 12, 'status' => 1],
            ['code' => '0060', 'name' => 'Kajang', 'state_id' => 12, 'status' => 1],
            ['code' => '0061', 'name' => 'Ampang', 'state_id' => 12, 'status' => 1],
            ['code' => '0062', 'name' => 'Sepang', 'state_id' => 12, 'status' => 1],
            ['code' => '0063', 'name' => 'Rawang', 'state_id' => 12, 'status' => 1],
            ['code' => '0064', 'name' => 'Cyberjaya', 'state_id' => 12, 'status' => 1],

            // Terengganu (state_id = 13)
            ['code' => '0065', 'name' => 'Kuala Terengganu', 'state_id' => 13, 'status' => 1],
            ['code' => '0066', 'name' => 'Kemaman', 'state_id' => 13, 'status' => 1],
            ['code' => '0067', 'name' => 'Dungun', 'state_id' => 13, 'status' => 1],
            ['code' => '0068', 'name' => 'Marang', 'state_id' => 13, 'status' => 1],
            ['code' => '0069', 'name' => 'Besut', 'state_id' => 13, 'status' => 1],
            ['code' => '0070', 'name' => 'Setiu', 'state_id' => 13, 'status' => 1],
        ];

        Location::insert($locations);
    }
}
