<?php

namespace Tests\Feature;

use Auth;
use App\Todo;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TodoTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_read_all_the_todos() {
        $this->actingAs(factory('App\User')->create());

        $todo = factory('App\Todo')->make();
        $this->post('/todo',$todo->toArray());
        $response = $this->get('/todo');
        $response->assertSee($todo->todo);
    }

    /** @test */
    public function a_user_can_read_single_todo() {
        //$this->withoutExceptionHandling();
        $this->actingAs(factory('App\User')->create());

        $todo = factory('App\Todo')->create();
        $this->post('/todo',$todo->toArray());
        $response = $this->get('/todo/'.$todo->id);
        $response->assertSee($todo->todo)
            ->assertSee($todo->description);
    }

    /** @test */
    public function authenticated_users_can_create_a_new_todo() {
        //$this->withoutExceptionHandling();
        //Given we have an authenticated user
        $this->actingAs(factory('App\User')->create());
        //And a task object
        $todo = factory('App\Todo')->make();
        //When user submits post request to create task endpoint
        $this->post('/todo',$todo->toArray());
        //It gets stored in the database
        $this->assertEquals(1,Todo::all()->count());
    }

    /** @test */
    public function unauthenticated_users_cannot_create_a_new_todo() {
        //And a task object
        $todo = factory('App\Todo')->make();
        //When user submits post request to create task endpoint
        $this->post('/todo',$todo->toArray())
                ->assertRedirect('/login');
    }

    public function a_todo_requires_a_title() {

        $this->withoutExceptionHandling();
        $this->actingAs(factory('App\User')->create());

        //$todo = factory('App\Todo')->make(['todo' => null]);
        $todo = factory('App\Todo')->make();
        print $todo;
        $this->post('/todo',$todo->toArray())
                ->assertSessionHasErrors();
    }

    public function a_todo_requires_a_description() {

        //$this->withoutExceptionHandling();
        $this->actingAs(factory('App\User')->create());

        //$todo = factory('App\Todo')->make(['description' => null]);

        $this->post('/todo',$todo->toArray())
            ->assertSessionHasErrors('description');
    }

       /** @test */
       public function authorized_user_can_update_the_todo(){

        $this->actingAs(factory('App\User')->create());
        $todo = factory('App\Todo')->create(['user_id' => Auth::id()]);
        $todo->todo = "Updated Title";
        $this->put('/todo/'.$todo->id, $todo->toArray());
        //The task should be updated in the database.
        $this->assertDatabaseHas('todo',['id'=> $todo->id , 'todo' => 'Updated Title']);
    }

    public function unauthorized_user_cannot_update_the_todo(){
        //Given we have a signed in user
        //$this->actingAs(factory('App\User')->create());
        $todo = factory('App\Todo')->create();
        print $todo;
        $todo->todo = "Updated Title";
        $this->post('/todo'.$todo->id,$todo->toArray())
                ->assertRedirect('/login');
    }

    public function authorized_user_can_delete_the_todo(){

        $this->actingAs(factory('App\User')->create());
        //$todo = factory('App\Todo')->create(['user_id' => Auth::id()]);
        $todo = factory('App\Todo')->create();
        $this->delete('/todo/'.$todo->id);
        $this->assertDatabaseMissing('tasks',['id'=> $todo->id]);
    }

    public function unauthorized_user_cannot_delete_the_todo(){
        //Given we have a signed in user
        $this->actingAs(factory('App\User')->create());
        //And a task which is not created by the user
        $task = factory('App\Todo')->create();
        //When the user hit's the endpoint to update the task
        $response = $this->delete('/todo/'.$task->id, $task->toArray())->assertRedirect('/todo');
    }
}