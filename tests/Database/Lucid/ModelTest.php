<?php

require_once 'Product.php';

use PHPUnit\Framework\TestCase;
use \Lightpack\Database\Lucid\Model;

final class ModelTest extends TestCase
{
    private $db;
    private $model;

    public function setUp(): void
    {
        $config = require __DIR__ . '/../tmp/mysql.config.php';

        $this->db = new \Lightpack\Database\Adapters\Mysql($config); 
        $this->product = $this->db->model(Product::class);
    }

    public function testModelInstance()
    {
        $this->assertInstanceOf(Model::class, $this->product);
    }

    public function testModelSaveInsertMethod()
    {
        $products = $this->db->table('products')->fetchAll();
        $productsCountBeforeSave = count($products);

        $this->product->name = 'Dummy Product';
        $this->product->color = '#CCC';
        $this->product->save();

        $products = $this->db->table('products')->fetchAll();
        $productsCountAfterSave = count($products);

        $this->assertEquals($productsCountBeforeSave + 1, $productsCountAfterSave);
    }

    public function testModelSaveUpdateMethod()
    {
        $product = $this->db->table('products')->fetchOne();
        $products = $this->db->table('products')->fetchAll();
        $productsCountBeforeSave = count($products);

        $this->product->find($product->id);
        $this->product->name = 'ACME Product';
        $this->product->save();

        $products = $this->db->table('products')->fetchAll();
        $productsCountAfterSave = count($products);

        $this->assertEquals($productsCountBeforeSave, $productsCountAfterSave);
    }

    public function testModelDeleteMethod()
    {
        $product = $this->db->table('products')->fetchOne();
        $products = $this->db->table('products')->fetchAll();
        $productsCountBeforeDelete = count($products);

        $this->product->find($product->id);
        $this->product->delete();

        $products = $this->db->table('products')->fetchAll();
        $productsCountAfterDelete = count($products);

        $this->assertEquals($productsCountBeforeDelete - 1, $productsCountAfterDelete);
    }

    public function testModelDeleteWhenIdNotSet()
    {
        $this->assertFalse($this->product->delete());
    }

    public function testModelHasOneRelation()
    {
        $this->db->table('products')->insert(['name' => 'Dummy Product', 'color' => '#CCC']);
        $product = $this->db->table('products')->orderBy('id', 'DESC')->fetchOne();
        $owner = $this->db->table('owners')->where('product_id', '=', $product->id)->fetchOne();
        
        if(!isset($owner->id)) {
            $this->db->table('owners')->insert(['product_id' => $product->id, 'name' => 'Bob']);
        }

        $this->product->find($product->id);
        $productOwner = $this->product->owner;
        $this->assertTrue(isset($productOwner->id));
    }

    public function testModelHasManyRelation()
    {
        $this->db->table('products')->insert(['name' => 'Dummy Product', 'color' => '#CCC']);
        $product = $this->db->table('products')->orderBy('id', 'DESC')->fetchOne();
        $this->db->table('options')->insert(['product_id' => $product->id, 'name' => 'Size', 'value' => 'XL']);
        $this->db->table('options')->insert(['product_id' => $product->id, 'name' => 'Color', 'value' => '#000']);
        
        $this->product->find($product->id);
        $productOptions = $this->product->options;
        $this->assertEquals(2, count($productOptions));
    }

    public function testModelBelongsToRelation()
    {
        $this->db->table('products')->insert(['name' => 'Dummy Product', 'color' => '#CCC']);
        $product = $this->db->table('products')->orderBy('id', 'DESC')->fetchOne();
        $owner = $this->db->table('owners')->where('product_id', '=', $product->id)->fetchOne();
        
        if(!isset($owner->id)) {
            $this->db->table('owners')->insert(['product_id' => $product->id, 'name' => 'Bob']);
            $owner = $this->db->table('owners')->where('product_id', '=', $product->id)->fetchOne();
        }

        $ownerModel = $this->db->model(Owner::class);
        $ownerModel->find($owner->id);
        $ownerProduct = $ownerModel->product;

        $this->assertTrue(isset($ownerProduct->id));
    }
}

