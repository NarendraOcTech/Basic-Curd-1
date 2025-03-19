<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/config.php';

use App\Models\Student;  // Import the Student model

use Slim\Psr7\Request;
use Slim\Psr7\Response;

// Fetch all students
$app->get('/students', function ($request, $response, $args) {
    $students = Student::all();
    $response->getBody()->write($students->toJson());
    return $response->withHeader('Content-Type', 'application/json');
});

// Fetch a single student by ID
$app->get('/students/{id}', function ($request, $response, $args) {
    $student = Student::find($args['id']);
    if ($student) {
        $response->getBody()->write($student->toJson());
    } else {
        $response->getBody()->write(json_encode(['error' => 'Student not found']));
    }
    return $response->withHeader('Content-Type', 'application/json');
});


// Create a new student gives form-data
$app->post('/students', function ($request, $response) {
    $data = $request->getParsedBody();
    
    $student = Student::create([
        'name'  => $data['name'],
        'age'   => $data['age'],
        'email' => $data['email']
    ]);

    $response->getBody()->write(json_encode(['message' => 'Student created', 'data' => $student]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
});


// Update a student  row json data is given
$app->put('/students/{id}', function (Request $request, Response $response, $args) {
    $id = $args['id'];

    
    $student = Student::find($args['id']);
    
    if ($student) {
        $data = json_decode($request->getBody()->getContents(), true);
        $student->update($data);
        $response->getBody()->write(json_encode(['message' => 'Student updated', 'data' => $student]));
    } else {
        $response->getBody()->write(json_encode(['error' => 'Student not found']));
    }
            
        
    return $response->withHeader('Content-Type', 'application/json');
});

// Delete a student
$app->delete('/students/{id}', function ($request, $response, $args) {
    $student = Student::find($args['id']);
    
    if ($student) {
        $student->delete();
        $response->getBody()->write(json_encode(['message' => 'Student deleted']));
    } else {
        $response->getBody()->write(json_encode(['error' => 'Student not found']));
    }
    
    return $response->withHeader('Content-Type', 'application/json');
});



$app->run();
