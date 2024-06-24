<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;

test('create product without logged in', function () {
    $response = $this->json('post', '/api/product', []);

    expect($response->original)->toMatchArray(['message' => 'Unauthenticated.']);
    $response->assertStatus(401);
});

test('create product without request parameter', function () {
    $this->actingAs(User::first());
    $response = $this->json('post', '/api/product', []);

    expect($response->original)->toMatchArray(['message' => 'The category id field is required. (and 5 more errors)']);
    $response->assertStatus(422);
});

test('create product', function () {
    $this->actingAs(User::first());
    $file = new UploadedFile(public_path('products/product-1.jpg'), 'product-1', null, null, true);
    $response = $this->json('post', '/api/product', [
        'category_id' => 1,
        'name' => 'Test Product',
        'description' => 'Test Description',
        'price' => '10000',
        'amount' => '5',
        'picture' => $file
    ]);

    $response->assertStatus(200);
});

test('edit product without update picture', function () {
    $this->actingAs(User::first());
    $product = App\Models\Product::where([
        'name' => 'Test Product',
        'description' => 'Test Description'
    ])->first();
    $response = $this->json('put', '/api/product/' . $product->id, [
        'category_id' => 1,
        'name' => 'Test Product',
        'description' => 'Test Description',
        'price' => '10000',
        'amount' => '10',
    ]);
});
