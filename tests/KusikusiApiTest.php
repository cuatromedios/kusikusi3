<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class KusikusiApiTest extends TestCase
{

    /** LOG IN */
    public function testLoginWithCorrectData()
    {
        global $argv, $argc;
        $json = [
            'username' => $argv[4],
            'password' => $argv[5]
        ];
        $user = $this->POST('/api/user/login', $json)->seeStatusCode(200)->response->getContent();
        $auth = json_decode($user, true);
        $authorizationToken = $auth['data']['token'];
        return $authorizationToken;
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
    public function testGetAllEntitiesWithCorrectToken($authorizationToken)
    {
        $response = $this->GET('/api/entity', ['HTTP_Authorization' => 'Bearer '.$authorizationToken]);
        $response->seeStatusCode(200);
    }
    public function testGetAllEntitiesWithIncorrectToken()
    {
        $response = $this->GET('/api/entity', ['HTTP_Authorization' => 'Bearer ' . 'IncorrectToken']);
        $response->seeStatusCode(401);
    }

    /**
     * @param string $id Id to test
     * @param int $status Expected status code
     *
     * @dataProvider IdProvider
     * @depends testLoginWithCorrectData
     */
    public function testGetEntityWithCorrectToken($id, $status, $authorizationToken)
    {
        $response = $this->GET('/api/entity/'.$id, ['HTTP_Authorization' => 'Bearer '.$authorizationToken]);
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
    public function testGetEntityParentWithCorrectToken($id, $status, $authorizationToken)
    {
        $response = $this->GET('/api/entity/'.$id.'/parent', ['HTTP_Authorization' => 'Bearer '.$authorizationToken]);
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
    public function testGetEntityChildrenWithCorrectToken($id, $status, $authorizationToken)
    {
        $response = $this->GET('/api/entity/'.$id.'/children', ['HTTP_Authorization' => 'Bearer '.$authorizationToken]);
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
    public function testGetEntityAncestorsWithCorrectToken($id, $status, $authorizationToken)
    {
        $response = $this->GET('/api/entity/'.$id.'/ancestors', ['HTTP_Authorization' => 'Bearer '.$authorizationToken]);
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
    public function testGetEntityDescendantsWithCorrectToken($id, $status, $authorizationToken)
    {
        $response = $this->GET('/api/entity/'.$id.'/descendants', ['HTTP_Authorization' => 'Bearer '.$authorizationToken]);
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
    public function testGetEntityRelationsWithCorrectToken($id, $kind, $status, $authorizationToken)
    {
        $response = $this->GET('/api/entity/'.$id.'/relations/'.$kind, ['HTTP_Authorization' => 'Bearer '.$authorizationToken]);
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
    public function testGetEntityInverseRelationsWithCorrectToken($id, $kind, $status, $authorizationToken)
    {
        $response = $this->GET('/api/entity/'.$id.'/inverse-relations/'.$kind, ['HTTP_Authorization' => 'Bearer '.$authorizationToken]);
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
    public function testPostEntityWithCorrectToken($authorizationToken)
    {
        $json = [
            'model' => 'page',
            'name' => 'TEST CREATED ENTITY ',
            'parent' => 'home'
        ];
        $response = $this->json('POST', '/api/entity', $json, ['HTTP_Authorization' => 'Bearer '.$authorizationToken]);
        $response->seeStatusCode(200);

        $auth = json_decode($response->response->getContent(), true);
        $entity_id = $auth['data']['id'];

        // print ("\nENTITY CREATED: ".$entity_id."\n");

        return $entity_id;
    }
    public function testPostEntityWithIncorrectToken()
    {
        $json = [
            'model' => 'medium',
            'name' => 'test',
            'parent' => 'media'
        ];
        $response = $this->json('POST', '/api/entity', $json, ['HTTP_Authorization' => 'Bearer ' . 'IncorrectToken']);
        $response->seeStatusCode(401);
    }

    /**
     * @depends testLoginWithCorrectData
     * @depends testPostEntityWithCorrectToken
     */
    public function testPostRelationWithCorrectToken($authorizationToken, $entity_id)
    {
        $json = [
            'id' => 'home',
            'kind' => 'relation'
        ];
        $response = $this->json('POST', '/api/entity/'.$entity_id.'/relations', $json, ['HTTP_Authorization' => 'Bearer '.$authorizationToken]);
        $response->seeStatusCode(200);
    }

    /**
     * @depends testPostEntityWithCorrectToken
     */
    public function testPostRelationWithIncorrectToken($entity_id)
    {
        $json = [
            'id' => 'home',
            'kind' => 'relation'
        ];
        $response = $this->json('POST', '/api/entity/'.$entity_id.'/relations', $json, ['HTTP_Authorization' => 'Bearer ' . 'IncorrectToken']);
        $response->seeStatusCode(401);
    }


    /** PATCH */
    /**
     *
     * @depends testLoginWithCorrectData
     * @depends testPostEntityWithCorrectToken
     */
    public function testPatchEntityWithCorrectToken($authorizationToken, $entity_id)
    {
        $json = [
            'isActive' => 0
        ];
        $response = $this->json('PATCH', '/api/entity/'.$entity_id, $json, ['HTTP_Authorization' => 'Bearer '.$authorizationToken]);
        $response->seeStatusCode(200);
    }
    /**
     * @depends testPostEntityWithCorrectToken
     */
    public function testPatchEntityWithIncorrectToken($entity_id)
    {
        $json = [
            'isActive' => 0
        ];
        $response = $this->json('PATCH', '/api/entity/'.$entity_id, $json, ['HTTP_Authorization' => 'Bearer '.'IncorrectToken']);
        $response->seeStatusCode(401);
    }

    /**
     * @depends testPostEntityWithCorrectToken
     */
    public function testDeleteRelationWithIncorrectToken($entity_id)
    {
        $response = $this->json('DELETE', '/api/entity/'.$entity_id.'/relations/home/relation', [], ['HTTP_Authorization' => 'Bearer '.'IncorrectToken']);
        $response->seeStatusCode(401);
    }
    /**
     * @depends testLoginWithCorrectData
     * @depends testPostEntityWithCorrectToken
     */
    public function testDeleteRelationWithCorrectToken($authorizationToken, $entity_id)
    {
        $response = $this->json('DELETE', '/api/entity/'.$entity_id.'/relations/home/relation', [], ['HTTP_Authorization' => 'Bearer '.$authorizationToken]);
        $response->seeStatusCode(200);
    }



    /** DELETE */

    /**
     * @depends testPostEntityWithCorrectToken
     */
    public function testSoftDeleteEntityWithIncorrectToken($entity_id)
    {
        $response = $this->json('DELETE', '/api/entity/'.$entity_id, [], ['HTTP_Authorization' => 'Bearer '.'IncorrectToken']);
        $response->seeStatusCode(401);
    }
    /**
     * @depends testLoginWithCorrectData
     * @depends testPostEntityWithCorrectToken
     */
    public function testSoftDeleteEntityWithCorrectToken($authorizationToken, $entity_id)
    {
        $response = $this->json('DELETE', '/api/entity/'.$entity_id, [], ['HTTP_Authorization' => 'Bearer '.$authorizationToken]);
        $response->seeStatusCode(200);
    }


    /**
     * @depends testPostEntityWithCorrectToken
     */
    public function testHardDeleteEntityWithIncorrectToken($entity_id)
    {
        $response = $this->json('DELETE', '/api/entity/'.$entity_id.'/hard', [], ['HTTP_Authorization' => 'Bearer '.'IncorrectToken']);
        $response->seeStatusCode(401);
    }
    /**
     * @depends testLoginWithCorrectData
     * @depends testPostEntityWithCorrectToken
     */
    public function testHardDeleteEntityWithCorrectToken($authorizationToken, $entity_id)
    {
        $response = $this->json('DELETE', '/api/entity/'.$entity_id.'/hard', [], ['HTTP_Authorization' => 'Bearer '.$authorizationToken]);
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