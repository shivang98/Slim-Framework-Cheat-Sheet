<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

// Get all customers
$app->get('/api/customers', function(Request $request, Response $response) {
    $sql = "SELECT * FROM customers";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($customers);

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'} }';
    }
});

// Get single customers
$app->get('/api/customers/{id}', function(Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $sql = "SELECT * FROM customers WHERE id = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $customer = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($customer);

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'} }';
    }
});

// Add customers
$app->post('/api/customers/add', function(Request $request, Response $response) {
    
    $name = $request->getParam('name');
    $address = $request->getParam('address');
    $phone = $request->getParam('phone');
    
    $sql = "INSERT INTO customers (name,address,phone) VALUES(:name,:address,:phone) ";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':name',$name);
        $stmt->bindParam(':address',$address);
        $stmt->bindParam(':phone',$phone);

        $stmt->execute();

        echo '{"Notice": {"text": "Customer Added"} }';

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'} }';
    }
});

// Update customers
$app->put('/api/customers/update/{id}', function(Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $name = $request->getParam('name');
    $address = $request->getParam('address');
    $phone = $request->getParam('phone');
    
    $sql = "UPDATE customers SET
            name = :name,
            address = :address,
            phone = :phone where id = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':name',$name);
        $stmt->bindParam(':address',$address);
        $stmt->bindParam(':phone',$phone);

        $stmt->execute();

        echo '{"Notice": {"text": "Customer update"} }';

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'} }';
    }
});

// Delete single customers
$app->delete('/api/customers/delete/{id}', function(Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $sql = "DELETE FROM customers WHERE id = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
        echo '{"Notice": {"text": "Customer deleted"} }';
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'} }';
    }
});