<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::today());
});

//user presignup
$router->group(['prefix' => 'admin', 'middleware' => 'admin'], function () use ($router) {
    $router->get('/users', 'users\userController@index');
    $router->get('/users/info/{id}', 'users\userController@usersInfo');
    $router->get('/users/{id}', 'users\userController@show');
    $router->post('/users/changeadmin', 'users\userController@changeadmin');
    $router->post('/users/reset', 'users\userController@reset');

    $router->get('/srchcard', 'units\FlashcardController@search');


    //Majormain 
    $router->group(['prefix' => 'majormain', 'namespace' => 'units'], function () use ($router) {
        $router->post('/', 'MajormainController@store');
        $router->delete('/{id}', 'MajormainController@delete');
        $router->put('/{id}', 'MajormainController@update');
        $router->get('/{id}', 'MajormainController@show');
        $router->get('/', 'MajormainController@showall');
    });

    //payments 
    $router->group(['prefix' => 'payments', 'namespace' => 'units'], function () use ($router) {
        $router->get('/', 'PaymentController@index');
    });

    // //Majortype 
    // $router->group(['prefix' => 'majortype', 'namespace' => 'units'], function () use ($router) {
    //     $router->post('/', 'MajortypeController@store');
    //     $router->delete('/{id}', 'MajortypeController@delete');
    //     $router->put('/{id}', 'MajortypeController@update');
    //     $router->get('/{parent_id}', 'MajortypeController@showall');
    //     //$router->get('/', 'MajortypeController@showall');
    // });

    //Major 
    $router->group(['prefix' => 'major', 'namespace' => 'units'], function () use ($router) {
        $router->post('/', 'MajorController@store');
        $router->delete('/{id}', 'MajorController@delete');
        $router->post('/{id}', 'MajorController@update');
        $router->get('/{parent_id}', 'MajorController@showall');
        //$router->get('/', 'MajorController@showall');
    });

    //Lesson 
    $router->group(['prefix' => 'lesson', 'namespace' => 'units'], function () use ($router) {
        $router->post('/', 'LessonController@store');
        $router->delete('/{id}', 'LessonController@delete');
        $router->post('/{id}', 'LessonController@update');
        $router->get('/{parent_id}', 'LessonController@showall');
        $router->get('/buyers/{parent_id}', 'LessonController@buyers');
        //$router->get('/', 'LessonController@showall');
    });


    //Unit 
    $router->group(['prefix' => 'unit', 'namespace' => 'units'], function () use ($router) {
        $router->post('/', 'CSVController@upload');
        $router->delete('/{id}', 'UnitController@delete');
        $router->post('/{id}', 'UnitController@update');
        $router->get('/{parent_id}', 'UnitController@showall');
        //$router->get('/', 'UnitController@showall');
    });


    //Flashcard 
    $router->group(['prefix' => 'flashcard', 'namespace' => 'units'], function () use ($router) {
        $router->post('/', 'FlashcardController@store');
        $router->delete('/{id}', 'FlashcardController@delete');
        $router->post('/{id}', 'FlashcardController@update');
        $router->post('/order/edit', 'FlashcardController@orederEdit');
        $router->get('/{parent_id}', 'FlashcardController@showall');
        $router->get('/search/{text}', 'FlashcardController@search');
    });


    //test
    $router->group(['prefix' => 'test', 'namespace' => 'test'], function () use ($router) {

        //test category
        $router->group(['prefix' => 'testcategory'], function () use ($router) {
            $router->get('/', 'TestCategoryController@index');
            $router->post('/', 'TestCategoryController@store');
            $router->delete('/{id}', 'TestCategoryController@delete');
            $router->put('/{id}', 'TestCategoryController@update');
            $router->get('/{id}', 'TestCategoryController@show');
        });

        //test answer
        $router->group(['prefix' => 'testanswer'], function () use ($router) {
            $router->get('/', 'TestAnswerController@index');
            $router->post('/', 'TestAnswerController@store');
            $router->delete('/{id}', 'TestAnswerController@delete');
            $router->put('/{id}', 'TestAnswerController@update');
            $router->get('/{id}', 'TestAnswerController@show');
        });

        //test question
        $router->group(['prefix' => 'testquestion'], function () use ($router) {
            $router->get('/', 'TestQuestionController@index');
            $router->get('/index/2', 'TestQuestionController@index2');
            $router->post('/', 'TestQuestionController@store');
            $router->delete('/{id}', 'TestQuestionController@delete');
            $router->post('/{id}', 'TestQuestionController@update');
            $router->get('/{id}', 'TestQuestionController@show');
        });

        $router->get('/', 'TestController@index');
        $router->post('/', 'TestController@store');
        $router->delete('/{id}', 'TestController@delete');
        $router->post('/{id}', 'TestController@update');
        $router->get('/{id}', 'TestController@show');
        $router->get('/buyers/{parent_id}', 'TestController@buyers');
    });

    //csv controller
    $router->group(['prefix' => 'csv', 'namespace' => 'units'], function () use ($router) {
        $router->get('/', 'CSVController@getArray');
        $router->post('/upload', 'CSVController@upload');
    });


    //ticket
    $router->group(['prefix' => 'ticket', 'namespace' => 'ticket'], function () use ($router) {
        $router->post('/createticket', ['middleware' => 'auth', 'uses' => 'TicketController@createTicket']);
        $router->post('/sendmessage', ['uses' => 'TicketController@sendMessage']);
        $router->post('/receivemessage', ['middleware' => 'admin', 'uses' => 'TicketController@receiveMessage']);
        $router->get('/', 'TicketController@index');
        $router->get('/{parent_id}', 'TicketController@show');
    });

    //video categories
    $router->group(['prefix' => 'video/category', 'namespace' => 'multimedia'], function () use ($router) {
        $router->post('/', 'VideoCategoryController@store');
        $router->delete('/{id}', 'VideoCategoryController@delete');
        $router->put('/{id}', 'VideoCategoryController@update');
        $router->get('/{id}', 'VideoCategoryController@show');
        $router->get('/', 'VideoCategoryController@showall');
    });

    //video
    $router->group(['prefix' => 'video', 'namespace' => 'multimedia'], function () use ($router) {
        $router->delete('/{id}', ['middleware' => 'admin', 'uses' => 'VideoController@delete']);
        $router->post('/upload', ['middleware' => 'admin', 'uses' => 'VideoController@upload']);
        $router->post('/edit/{id}', ['middleware' => 'admin', 'uses' => 'VideoController@edit']);
        $router->get('/buyers/{parent_id}', 'VideoController@buyers');
    });

    //pdf categories
    $router->group(['prefix' => 'pdf/category', 'namespace' => 'multimedia'], function () use ($router) {
        $router->post('/', 'PDFCategoryController@store');
        $router->delete('/{id}', 'PDFCategoryController@delete');
        $router->put('/{id}', 'PDFCategoryController@update');
        $router->get('/{id}', 'PDFCategoryController@show');
        $router->get('/', 'PDFCategoryController@showall');
    });

    //pdf
    $router->group(['prefix' => 'pdf', 'namespace' => 'multimedia'], function () use ($router) {
        $router->delete('/{id}', ['middleware' => 'admin', 'uses' => 'PDFController@delete']);
        $router->post('/upload', ['middleware' => 'admin', 'uses' => 'PDFController@upload']);
        $router->post('/edit/{id}', ['middleware' => 'admin', 'uses' => 'PDFController@edit']);
        $router->get('/buyers/{parent_id}', 'PDFController@buyers');
    });

    //options
    $router->group(['prefix' => 'option', 'middleware' => 'admin'], function () use ($router) {
        $router->post('/', 'OptionsController@store');
        $router->delete('/{id}', 'OptionsController@delete');
        $router->put('/{id}', 'OptionsController@update');
        $router->get('/{name}', 'OptionsController@show');
        $router->get('/', 'OptionsController@showall');
    });


    //Unit 
    $router->group(['prefix' => 'notification'], function () use ($router) {
        $router->post('/', 'NotificationController@store');
        $router->delete('/{id}', 'NotificationController@delete');
        $router->put('/{id}', 'NotificationController@update');
        $router->get('/{id}', 'NotificationController@show');
        $router->get('/', 'NotificationController@showall');
    });


    //takhfif
    $router->group(['prefix' => 'takhfif'], function () use ($router) {
        $router->get('/', 'TakhfifController@showall');
        $router->get('/{id}', 'TakhfifController@show');
        $router->post('/makegroup', 'TakhfifController@makeGroup');
        $router->get('/groups/get', 'TakhfifController@Groups');
        $router->delete('/groups/delete/{id}', 'TakhfifController@DeleteGroup');
        $router->post('/', 'TakhfifController@store');
        $router->delete('/{id}', 'TakhfifController@delete');
    });

    $router->group(['prefix' => 'app'], function () use ($router) {
        $router->post('/', 'UpdatingController@uploadFile');
        $router->post('/deleteapp', 'UpdatingController@deleteApp');
    });

});





//Unit 





//user presignup
$router->group(['prefix' => 'users', 'namespace' => 'users'], function () use ($router) {
    $router->post('presignup', 'userController@preSignup');
    $router->post('checkotp', 'userController@checkOTP');
    $router->post('completesignup',  ['middleware' => 'auth', 'uses' => 'userController@completeSignup']);
});

///client
///client
///client
///client
$router->group(['prefix' => 'zarinpal'], function () use ($router) {
    $router->get('/', 'ZarinpalController@createPayment');
    $router->get('/paymentresponse', 'ZarinpalController@paymentResponse');
});
//user presignup
$router->group(['prefix' => 'user', 'middleware' => 'auth'], function () use ($router) {
    //Major 
    $router->group(['prefix' => 'major', 'namespace' => 'units'], function () use ($router) {
        $router->get('/{parent_id}', 'MajorController@showall');
    });

    //Lesson 
    $router->group(['prefix' => 'lesson', 'namespace' => 'units'], function () use ($router) {
        $router->get('/{parent_id}', 'LessonController@showall');
    });

    //Unit 
    $router->group(['prefix' => 'unit', 'namespace' => 'units'], function () use ($router) {
        $router->get('/{parent_id}', 'UnitController@showallforapp');
    });

    //Flashcard
    $router->group(['prefix' => 'flashcard', 'namespace' => 'units'], function () use ($router) {

        $router->get('/ids/{parent_id}', 'FlashcardController@getIds');
        $router->get('/srch/ids', 'FlashcardController@srch');
        $router->get('/{id}', 'FlashcardController@show');
        $router->post('/', 'FlashcardController@storeFree');
        $router->post('/edit/{id}', 'FlashcardController@editFree');
        $router->get('/free/showall', 'FlashcardController@showallFree');
        $router->delete('/free/{id}', 'FlashcardController@deletefree');
        $router->post('/save/note', 'FlashcardController@saveNote');
    });

    //Favorite
    $router->group(['prefix' => 'favorite', 'namespace' => 'units'], function () use ($router) {

        $router->post('/', 'FavoriteController@store');
        $router->get('/', 'FavoriteController@showall');
        $router->get('/get/ids', 'FavoriteController@getIds');
        $router->delete('/{id}', 'FavoriteController@delete');
    });

    // Leitner Box 
    $router->group(['prefix' => 'leitner', 'namespace' => 'units'], function () use ($router) {
        $router->post('/', 'LeitnerController@moveToBox');
        $router->get('/', 'LeitnerController@getBox');
        $router->delete('/{id}', 'LeitnerController@removeCard');
        $router->get('/get/flashcards', 'LeitnerController@getUserFlashcards');
        $router->get('/get/flashcardsids', 'LeitnerController@getUserFlashcardIds');
        $router->get('/get/flashcards/{id}', 'LeitnerController@getUserFlashcardsbylevel');
        $router->get('/get/readyflashcards', 'LeitnerController@getUserReadyFlashcards');
        $router->get('/get/flashcard/{id}', 'LeitnerController@getUserFlashcard');
        $router->get('/get/flashcardsfinished', 'LeitnerController@getUserFlashcardsFinished');
        $router->get('/get/sync', 'LeitnerController@sync');
        $router->get('/get/readytoread', 'LeitnerController@readyToRead');
        $router->post('/get/grow', 'LeitnerController@grow');
        $router->post('/get/fall', 'LeitnerController@fall');
        $router->get('/get/counter', 'LeitnerController@counter');
    });
    //order 
    $router->group(['prefix' => 'order'], function () use ($router) {
        $router->post('/createpayment', 'ZarinpalController@createPayment');
        $router->get('/checkcode', 'TakhfifController@checkCode');
    });
    //notification 
    $router->group(['prefix' => 'notification'], function () use ($router) {
        $router->get('/', 'NotificationController@showall');
        $router->get('/get/notseen', 'NotificationController@notseen');
    });

    //ticket
    $router->group(['prefix' => 'ticket', 'namespace' => 'ticket'], function () use ($router) {
        $router->post('/createticket', 'TicketController@createTicket');
        $router->post('/sendmessage', 'TicketController@sendMessage');
        $router->get('/', 'TicketController@getTickets');
        $router->get('/get/notseen', 'TicketController@getTicketsNotseen');
        $router->get('/{parent_id}', 'TicketController@show');
    });

    //free flashcard
    $router->group(['prefix' => 'freeflashcard', 'namespace' => 'units'], function () use ($router) {
        $router->post('/', 'FreeFlashcardController@store');
        $router->get('/', 'FreeFlashcardController@index');
        $router->delete('/{id}', 'FreeFlashcardController@delete');
        $router->post('/ids', 'FreeFlashcardController@ids');
    });

    //video categories
    $router->group(['prefix' => 'video/category', 'namespace' => 'multimedia'], function () use ($router) {
        $router->get('/{id}', 'VideoCategoryController@show');
        $router->get('/', 'VideoCategoryController@showall');
    });

     //pdf categories
     $router->group(['prefix' => 'pdf/category', 'namespace' => 'multimedia'], function () use ($router) {
        $router->get('/{id}', 'PDFCategoryController@show');
        $router->get('/', 'PDFCategoryController@showall');
    });

     //test
     $router->group(['prefix' => 'test', 'namespace' => 'test'], function () use ($router) {
        //test category
        $router->group(['prefix' => 'testcategory'], function () use ($router) {
            $router->get('/', 'TestCategoryController@index');
            $router->get('/{id}', 'TestCategoryController@show');
        });

        //test category
        $router->group(['prefix' => 'asks'], function () use ($router) {
            $router->get('/', 'TestQuestionController@index');
            $router->get('/count', 'TestQuestionController@count');
        });
        
        $router->get('/', 'TestController@index');
        $router->get('/{id}', 'TestController@show');
        $router->get('/show/{id}', 'TestController@show1');
        $router->post('/saveresult', 'TestController@saveresult');
    });
    
    //app
    $router->group(['prefix' => 'app'], function () use ($router) {
        $router->get('/', 'UpdatingController@getApp');
    });

    $router->post('/invite', 'TakhfifController@addInvited');

    $router->post('/findposition', 'users\userController@findPositionAXIOS');
    $router->get('/options/{name}', 'OptionsController@show');
    $router->get('/freecsv/{user_id}', 'units\FreeFlashcardController@createCSV');
});


