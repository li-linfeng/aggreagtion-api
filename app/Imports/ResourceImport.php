<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Resource;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Carbon\Carbon;

class ResourceImport implements ToCollection, WithHeadingRow, WithEvents
{
    use Importable, RegistersEventListeners;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $category = Category::firstOrCreate([
                'name' => $row['category'] ?: "其他",
            ]);
            $resource = [
                'description' => $row['description'],
                'name'        => $row['rss'],
                'link'        => $row['link'],
                'category_id' => $category->id,
                'created_at'  => Carbon::now()->toDateTimeString(),
            ];
            Resource::create($resource);
        }
    }
}
