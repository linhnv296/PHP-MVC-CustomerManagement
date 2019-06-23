<?php

namespace Controller;

use Model\Customer;
use Model\CustomerDB;
use Model\DBConnection;
use PHPUnit\Runner\Exception;
use PDO;

class CustomerController
{
    public $customerDB;

    public function __construct()
    {
            $connection = new DBConnection("mysql:host=localhost; dbname=customerManagement", "root", "123456@Abc");
            $this->customerDB = new CustomerDB($connection->connect());
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            include 'View/add.php';
        } else {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $address = $_POST['address'];

            $customer = new Customer($name, $email, $address);
            $this->customerDB->create($customer);
            $message = 'Customer Create';
            include 'View/add.php';
        }
    }

    public function index()
    {
        $customers = $this->customerDB->getAll();
        include 'View/list.php';
    }
    public function delete(){
        if ($_SERVER['REQUEST_METHOD']=='GET'){
            $id = $_GET['id'];
            $customer = $this->customerDB->delete($id);
            include 'View/delete.php';
        }else{
            $id = $_POST['id'];
            $this->customerDB->delete($id);
            header('Location: index.php');
        }
    }

    public function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $id = $_GET['id'];
            $customer = $this->customerDB->get($id);
            include 'View/edit.php';
        } else {
            $id = $_POST['id'];
            $customer = new Customer($_POST['name'], $_POST['email'], $_POST['address']);
            $this->customerDB->update($id, $customer);
            header('Location: index.php');
        }
    }
}