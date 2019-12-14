<?php

namespace Tests\Feature;

use App\Book;
use Tests\TestCase;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\BookController;

class ExcelGenerateTest extends TestCase
{
     /**
     * Test For generate export.
     *
     * @return void
     */
    /** @test */
    public function generateExcel() 
    {
        // $response = $this->post('/generateExcel'.[]);
        // $response->assertStatus(200);

        $books = factory('App\Book', 5)->create();

        $response = $this->actingAs($books->first())->get('/generateExcel');
        $content = $response->streamedContent();
        // dd($content);

    }
    
}
