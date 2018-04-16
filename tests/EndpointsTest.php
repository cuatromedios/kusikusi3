<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class EndpointsTest extends TestCase
{

    /** LOG IN */
    public function testLoginWithCorrectData()
    {
        $json = [
            'email' => 'admin',
            'password' => 'P&d3!xLk85GT'
        ];
        $user = $this->POST('/api/user/login', $json)->seeStatusCode(200)->response->getContent();
        $auth = json_decode($user, true);
        $authorization = $auth['data']['token'];
        return $authorization;
    }

    public function testLoginWithIncorrectData()
    {
        $json = [
            'email' => 'kusikusi',
            'password' => 'IncorrectPassword'
        ];
        $this->POST('/api/user/login', $json)->seeStatusCode(401);

    }



    /** GET */
    /**
     * @depends testLoginWithCorrectData
     */
    public function testGetAllEntitiesWithCorrectToken($authorization)
    {
        $response = $this->GET('/api/entity/', ['HTTP_Authorization' => 'Bearer '.$authorization]);
        $response->seeStatusCode(200);
    }
    public function testGetAllEntitiesWithIncorrectToken()
    {
        $response = $this->GET('/api/entity/', ['HTTP_Authorization' => 'Bearer ' . 'IncorrectToken']);
        $response->seeStatusCode(401);
    }

    /**
     * @param string $id Id to test
     * @param int $status Expected status code
     *
     * @dataProvider IdProvider
     * @depends testLoginWithCorrectData
     */
    public function testGetEntityWithCorrectToken($id, $status, $authorization)
    {
        $response = $this->GET('/api/entity/'.$id, ['HTTP_Authorization' => 'Bearer '.$authorization]);
        $response->seeStatusCode($status);
    }
    /**
     * @param string $id Id to test
     * @param int $status Expected status code
     *
     * @dataProvider IdProvider
     */
    public function testGetEntityWithIncorrectToken($id)
    {
        $response = $this->GET('/api/entity/'.$id, ['HTTP_Authorization' => 'Bearer ' . 'IncorrectToken']);
        $response->seeStatusCode(401);
    }

    /**
     * @param string $id Id to test
     * @param int $status Expected status code
     *
     * @dataProvider ParentDataProvider
     * @depends testLoginWithCorrectData
     */
    public function testGetEntityParentWithCorrectToken($id, $status, $authorization)
    {
        $response = $this->GET('/api/entity/'.$id.'/parent', ['HTTP_Authorization' => 'Bearer '.$authorization]);
        $response->seeStatusCode($status);
    }
    /**
     * @param string $id Id to test
     * @param int $status Expected status code
     *
     * @dataProvider ParentDataProvider
     */
    public function testGetEntityParentWithIncorrectToken($id)
    {
        $response = $this->GET('/api/entity/'.$id.'/parent', ['HTTP_Authorization' => 'Bearer ' . 'IncorrectToken']);
        $response->seeStatusCode(401);
    }

    /**
     * @param string $id Id to test
     * @param int $status Expected status code
     *
     * @dataProvider IdProvider
     * @depends testLoginWithCorrectData
     */
    public function testGetEntityChildrenWithCorrectToken($id, $status, $authorization)
    {
        $response = $this->GET('/api/entity/'.$id.'/children', ['HTTP_Authorization' => 'Bearer '.$authorization]);
        $response->seeStatusCode($status);
    }
    /**
     * @param string $id Id to test
     * @param int $status Expected status code
     *
     * @dataProvider IdProvider
     */
    public function testGetEntityChildrenWithIncorrectToken($id)
    {
        $response = $this->GET('/api/entity/'.$id.'/children', ['HTTP_Authorization' => 'Bearer ' . 'IncorrectToken']);
        $response->seeStatusCode(401);
    }

    /**
     * @param string $id Id to test
     * @param int $status Expected status code
     *
     * @dataProvider IdProvider
     * @depends testLoginWithCorrectData
     */
    public function testGetEntityAncestorsWithCorrectToken($id, $status, $authorization)
    {
        $response = $this->GET('/api/entity/'.$id.'/ancestors', ['HTTP_Authorization' => 'Bearer '.$authorization]);
        $response->seeStatusCode($status);
    }
    /**
     * @param string $id Id to test
     * @param int $status Expected status code
     *
     * @dataProvider IdProvider
     */
    public function testGetEntityAncestorsWithIncorrectToken($id)
    {
        $response = $this->GET('/api/entity/'.$id.'/ancestors', ['HTTP_Authorization' => 'Bearer ' . 'IncorrectToken']);
        $response->seeStatusCode(401);
    }

    /**
     * @param string $id Id to test
     * @param int $status Expected status code
     *
     * @dataProvider IdProvider
     * @depends testLoginWithCorrectData
     */
    public function testGetEntityDescendantsWithCorrectToken($id, $status, $authorization)
    {
        $response = $this->GET('/api/entity/'.$id.'/descendants', ['HTTP_Authorization' => 'Bearer '.$authorization]);
        $response->seeStatusCode($status);
    }
    /**
     * @param string $id Id to test
     *
     * @dataProvider IdProvider
     */
    public function testGetEntityDescendantsWithIncorrectToken($id)
    {
        $response = $this->GET('/api/entity/'.$id.'/descendants', ['HTTP_Authorization' => 'Bearer ' . 'IncorrectToken']);
        $response->seeStatusCode(401);
    }

    /**
     * @param string $id Id to test
     * @param string $kind The specific type of relations
     * @param int $status Expected status code
     *
     * @dataProvider RelationDataProvider
     * @depends testLoginWithCorrectData
     */
    public function testGetEntityRelationsWithCorrectToken($id, $kind, $status, $authorization)
    {
        $response = $this->GET('/api/entity/'.$id.'/relations/'.$kind, ['HTTP_Authorization' => 'Bearer '.$authorization]);
        $response->seeStatusCode($status);
    }
    /**
     * @param string $id Id to test
     * @param string $kind The specific type of relations
     *
     * @dataProvider RelationDataProvider
     */
    public function testGetEntityRelationsWithIncorrectToken($id, $kind)
    {
        $response = $this->GET('/api/entity/'.$id.'/relations/'.$kind, ['HTTP_Authorization' => 'Bearer ' . 'IncorrectToken']);
        $response->seeStatusCode(401);
    }

    /**
     * @param string $id Id to test
     * @param string $kind The specific type of relations
     * @param int $status Expected status code
     *
     * @dataProvider RelationDataProvider
     * @depends testLoginWithCorrectData
     */
    public function testGetEntityInverseRelationsWithCorrectToken($id, $kind, $status, $authorization)
    {
        $response = $this->GET('/api/entity/'.$id.'/inverse-relations/'.$kind, ['HTTP_Authorization' => 'Bearer '.$authorization]);
        $response->seeStatusCode($status);
    }
    /**
     * @param string $id Id to test
     * @param string $kind The specific type of relations
     *
     * @dataProvider RelationDataProvider
     */
    public function testGetEntityInverseRelationsWithIncorrectToken($id, $kind)
    {
        $response = $this->GET('/api/entity/'.$id.'/inverse-relations/'.$kind, ['HTTP_Authorization' => 'Bearer ' . 'IncorrectToken']);
        $response->seeStatusCode(401);
    }



    /** POST */
    /**
     * @depends testLoginWithCorrectData
     */
    public function testPostEntityWithCorrectToken($authorization)
    {
        $json = [
            'model' => 'medium',
            'name' => 'test',
            'parent' => 'media'
        ];
        $response = $this->POST('/api/entity/', $json, ['HTTP_Authorization' => 'Bearer '.$authorization]);
        $response->seeStatusCode(200);
    }
    public function testPostEntityWithIncorrectToken()
    {
        $json = [
            'model' => 'medium',
            'name' => 'test',
            'parent' => 'media'
        ];
        $response = $this->POST('/api/entity/', $json, ['HTTP_Authorization' => 'Bearer ' . 'IncorrectToken']);
        $response->seeStatusCode(401);
    }

    /**
     * @depends testLoginWithCorrectData
     */
    public function testPostRelationWithCorrectToken($authorization)
    {
        $json = [
            'id' => 'home'
        ];
        $response = $this->POST('/api/entity/media/relations', $json, ['HTTP_Authorization' => 'Bearer '.$authorization]);
        $response->seeStatusCode(200);
    }
    public function testPostRelationWithIncorrectToken()
    {
        $json = [
            'id' => 'home'
        ];
        $response = $this->POST('/api/entity/media/relations', $json, ['HTTP_Authorization' => 'Bearer ' . 'IncorrectToken']);
        $response->seeStatusCode(401);
    }


    /** PATCH */
    /**
     * @param string $id Id to test
     * @param int $status Expected status code
     *
     * @dataProvider IdProvider
     * @depends testLoginWithCorrectData
     */
    public function testPatchEntityWithCorrectToken($id, $status, $authorization)
    {
        $json = [
            'isActive' => 0
        ];
        $response = $this->PATCH('/api/entity/'.$id, $json, ['HTTP_Authorization' => 'Bearer '.$authorization]);
        $response->seeStatusCode($status);
    }
    /**
     * @param string $id Id to test
     * @param int $status Expected status code
     *
     * @dataProvider IdProvider
     */
    public function testPatchEntityWithIncorrectToken($id)
    {
        $json = [
            'isActive' => 0
        ];
        $response = $this->PATCH('/api/entity/'.$id, $json, ['HTTP_Authorization' => 'Bearer '.'IncorrectToken']);
        $response->seeStatusCode(401);
    }



    /** DELETE */
    public function testSoftDeleteEntityWithIncorrectToken()
    {
        $response = $this->DELETE('/api/entity/root', [], ['HTTP_Authorization' => 'Bearer '.'IncorrectToken']);
        $response->seeStatusCode(401);
    }
    /**
     * @depends testLoginWithCorrectData
     */
    public function testSoftDeleteEntityWithCorrectToken($authorization)
    {
        $response = $this->DELETE('/api/entity/users', [], ['HTTP_Authorization' => 'Bearer '.$authorization]);
        $response->seeStatusCode(200);
    }

    public function testHardDeleteEntityWithIncorrectToken()
    {
        $response = $this->DELETE('/api/entity/root', [], ['HTTP_Authorization' => 'Bearer '.'IncorrectToken']);
        $response->seeStatusCode(401);
    }
    /**
     * @depends testLoginWithCorrectData
     */
    public function testHardDeleteEntityWithCorrectToken($authorization)
    {
        $response = $this->DELETE('/api/entity/media', [], ['HTTP_Authorization' => 'Bearer '.$authorization]);
        $response->seeStatusCode(200);
    }

    public function testDeleteRelationWithIncorrectToken()
    {
        $response = $this->DELETE('/api/entity/home/relations/root/ancestor', [], ['HTTP_Authorization' => 'Bearer '.'IncorrectToken']);
        $response->seeStatusCode(401);
    }
    /**
     * @depends testLoginWithCorrectData
     */
    public function testDeleteRelationWithCorrectToken($authorization)
    {
        $response = $this->DELETE('/api/entity/home/relations/root/ancestor', [], ['HTTP_Authorization' => 'Bearer '.$authorization]);
        $response->seeStatusCode(200);
    }


    /** DATA PROVIDERS */
    public function IdProvider()
    {
        return [
            ['root', 200],
            ['home', 200],
            ['media', 200],
            ['users', 200],
            ['IncorrectID', 404]
        ];
    }

    public function ParentDataProvider()
    {
        return [
            ['root', 404],
            ['home', 200],
            ['media', 200],
            ['users', 200],
            ['IncorrectID', 404]
        ];
    }

    public function RelationDataProvider()
    {
        return [
            ['root', 'ancestor', 200],
            ['root', 'medium', 200],
            ['home', 'ancestor', 200],
            ['home', 'medium', 200],
            ['media', 'ancestor', 200],
            ['media', 'medium', 200],
            ['users', 'ancestor', 200],
            ['users', 'medium', 200],
            ['IncorrectID', 'ancestors', 404],
            ['IncorrectID', 'medium', 404]
        ];
    }
}