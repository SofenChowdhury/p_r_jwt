<html>
    <body>
<div>
    <h6>
    <img width="200" height="60" src="https://fairelectronics.com.bd/pub/media/logo/Fair-Electronics_1_.png" alt="">
    </h6>
    <hr>
    <p>Dear Concern,</p>
    <p>A payment has been made by {{$user->name}} - Employee ID: {{$user->eid}} - Mobile Number: {{$booking->mob_code}}</p>
    
        <h5>
            Order Details as follow
        </h5>
    <p>
        
    </p>
    <p>Item Name: {{$booking->stock->product->model}}</p> 
    <p>
        Item Sku: {{$booking->stock->product->item_model}}
    </p>
    <p>
        Item Type: {{$booking->type}}
    </p>
    <p>
        Grade: {{$booking->grade}}
    </p>
    <p>
        Price: {{$booking->price}}
    </p>
    <p>
        Please click on the below link for see the uploaded deposit clip. 
    </p>
    <p>
        "http://3.112.249.37/storage/file_url/{{$pic}}"
    </p>
</div>
</body>
</html>