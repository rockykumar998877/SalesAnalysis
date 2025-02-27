<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Sale;

class SalesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_import_sales_data()
    {
        $csvContent = "Product,Quantity,Total Sales,Date\nLaptop,10,50000,2024-01-10";
        $file = tmpfile();
        fwrite($file, $csvContent);
        $path = stream_get_meta_data($file)['uri'];

        $response = $this->post(route('sales.import'), ['csv_file' => new \Illuminate\Http\UploadedFile($path, 'sales.csv', null, null, true)]);
        $response->assertSessionHas('success', 'Sales data imported successfully.');
    }
}
