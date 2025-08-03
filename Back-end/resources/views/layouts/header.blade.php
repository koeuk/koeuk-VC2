<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<header style="position: fixed; top: 0; width: 80%; z-index: 1000;">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="#">
        <span class="panel"><i class="bx bxl-bootstrap"></i>Admin Panel</span>
      </a>
      <div class="d-flex align-items-center">
      <div data-bs-toggle="modal" data-bs-target="#messageModal">
        <a class=" nav-link dropdown-toggle" id="messagesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bx bx-mail-send"></i>
          @php 
            $mess = $messages->where('receiver_id', 1)->where('is_read', 0)->count();
          @endphp
          @if ($mess!=0)
          <span class="badge bg-warning rounded-pill">{{$mess}}</span>
          @endif
        </a>
      </div>
      <div class="dropdown ms-3">
        <a class="nav-link dropdown-toggle" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bx bx-bell"></i>
          @if(count($feedbacks) !=0)
          <span class="badge bg-danger rounded-pill">{{count($feedbacks)}}</span>
          @endif
          </a>
        <ul class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="notificationsDropdown" style="width: 400px; max-height: 400px; overflow-y: auto;">
          <div class="notification-list space-y-2" style="height: 300px; overflow-y: scroll;">
          @if(count($feedbacks) !=0)
          @foreach ($feedbacks as $feedback)
            @php 
            $user = $users->where('id',$feedback->user_id)->first();
            @endphp
            {{-- ----------------------------------- --}}
            <a href="#" class="dropdown-item d-flex border border-warning justify-content-between items-center p-2 rounded-lg shadow-md hover:scale-105 transition-all duration-300">
              <div class="title relative w-50 d-flex flex-col align-item-center pt-2">
                <h5 class="card-title fw-bold mb-0 truncate" style="font-size:13px;">{{$user->name}}</h5>
                <p class="text-600 fw-bold text-sm truncate" style="font-size:10px;">{{$feedback->created_at->format('Y-m-d')}}
                </p>
              </div>
              <div class="text-2">
                {{$feedback->content}}
              </div>
            </a>
            {{-- --------------------------- --}}
            @endforeach
            @endif
            
          </div>
        </ul>
      </div>
  <div class="dropdown ms-3">
  <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
  <img src="{{ auth()->user()->profile }}" class="rounded-circle me-2" alt="Profile Picture" style="width: 40px; height: 40px; object-fit: cover;">    <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
  </a>
  <ul class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="profileDropdown" style="width: 200px;">
    <li>
      <a href="{{ route('admin.profile') }}" class="dropdown-item d-flex align-items-center py-2 rounded-lg hover:bg-gray-100 transition-colors duration-300">
        <i class="bx bx-user text-warning me-2"></i>
        <span class="text-gray-800">Profile</span>
      </a> 
    </li>
    <li>
      <a href="#" class="dropdown-item d-flex align-items-center py-2 rounded-lg hover:bg-gray-100 transition-colors duration-300">
        <i class="bx bx-cog text-secondary me-2"></i>
        <span class="text-gray-800">Settings</span>
      </a>
    </li>
    <li>
        {{-- <span class="text-gray-800">Logout {{route('admin.logout')}}</span> --}}
        {{-- <form  action="{{route('admin.logout')}}" method="POST"></form> --}}
        <form method="POST" action="{{ route('admin.logout') }}">
          @csrf
              <a href="{{ route('admin.logout') }}" onclick="event.preventDefault();
                                          this.closest('form').submit();"
              class="dropdown-item d-flex align-items-center py-2 rounded-lg hover:bg-gray-100 transition-colors duration-300">        <i class="bx bx-log-out text-danger me-2">  logout</i>
            </a>
          </form>
      </a>
    </li>
  </ul>
</div>
<i class='bx bx-menu' style='color:#f7f7f7;display:none;'></i>
      </div>
    </div>
  </nav>
</header>
<!-- ---------------------top service detail------------------ -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> -->

<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header bg-warning text-white h-3">
        <h5 class="modal-title" id="messageModalLabel">Messaging</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <!-- User List -->
          <div class="col-md-4 border-end ">
            <div class="input-group mb-3">
              <input type="text" class="form-control" placeholder="Search..." aria-label="Search" aria-describedby="button-addon2">
              <button class="btn btn-warning" type="button" id="button-addon2"><i class="bx bx-search"></i></button>
            </div>
            <div class="d-flex justify-content-around mb-2">
              <a href="#" id='showall' class="btn-hover-border-bottom">Show All</a>
              <a href="#" id='fixers' class="btn-hover-border-bottom">Fixer</a>
              <a href="#" id='customers' class="btn-hover-border-bottom">Customer</a>
            </div>
            <div class="list-group d-flex gap-3" id="userList" style="height: 430px; overflow-y: auto;">
              <!-- Example User List Item -->
              @php 
                $sender = [];
              @endphp
              @foreach ($messages as $message)
                @if ($message->receiver_id == 1 && !in_array($message->sender_id, $sender))
                    @php 
                      foreach ($messages as $messag){
                        if(($messag->receiver_id == 1 && $messag->sender_id == $message->sender_id)){
                          $mess= $messag->message;
                          $date=$messag->created_at;
                          $is_read = $messag->is_read;
                        }elseif(($messag->receiver_id == $message->sender_id  && $messag->sender_id ==1 )){
                          $mess= $messag->message;
                          $date=$messag->created_at;
                          $is_read = 1;
                        }
                      } 
                        $sender[] = $message->sender_id;
                        $user = $users->where('id', $message->sender_id)->first();
                      @endphp
                 <!-- --------------------------------- -->
                 <div id="{{ $user->role == 'fixer' ? 'fixer' : 'customer' }}" class="btn message-card rounded-lg p-3 shadow-md hover:scale-105 transition-all">
                    <div id='{{$user->id}}' class="sen d-flex items-center">
                      <div class="position-relative">
                        <img src="{{$user->profile}}" class="rounded-circle" alt="Profile Image" style="height: 3rem; width: 3rem; object-fit: cover;">
                        <span class="position-absolute bottom-0 end-0 translate-middle p-1 bg-success border border-light rounded-circle">
                          <span class="visually-hidden">Online</span>
                        </span>
                      </div>
                      <div class="flex-grow-1 text-start ms-3">
                        <h5 class="card-title font-bold mb-1" style="font-size:0.875rem;">{{$user->name}}</h5>
                        @if ($is_read==0 && $user->role!="admin")
                        <p class=" mb-0 truncate" style="font-size:0.75rem;"><strong>{{$mess}}</strong></p>
                        @elseif($is_read==1 && $user->role!="admin")
                        <p class="text-gray-600 mb-0 truncate" style="font-size:0.75rem;">{{$mess}}</p>
                        @else
                        <p class="text-gray-600 mb-0 truncate" style="font-size:0.75rem;">You: {{$mess}}</p>
                        @endif
                      </div>
                      @if ($user->role=='fixer')
                        <span class="text-white rounded px-2 py-1 ms-3 mr-6" style="font-size:2em;">👷‍♂️</span>
                      @else
                      <span class="bg-warning text-white rounded px-2 py-1 ms-3 mr-6" style="font-size:0.50rem;">Customer</span>
                      @endif
                      <p class="text-gray-600 mb-0 truncate" style="font-size:0.70rem;">{{$date->format('Y-m-d')}}</p>
                    </div>
                  </div>
                <!-- -------------------------------- -->
                 @endif
                @endforeach
                  <!-- Add more user items here -->
            </div>
          </div>
          @php
$sent = [];
@endphp
@foreach ($messages as $message)
    @if ($message->receiver_id == 1 && !in_array($message->sender_id, $sent))
        @php
            $sent[] = $message->sender_id;
            $account = $users->where('id', $message->sender_id)->first();
        @endphp
        <div id='sender{{ $account->id }}' class="discussion col-md-8" style='display:none'>
            <div class="d-flex align-items-center gap-3 p-2">
                <img src="{{ $account->profile }}" class="rounded-circle" alt="Profile Image" style="height: 3rem; width: 3rem; object-fit: cover;">
                <h5 class="card-title fw-bold mb-0 truncate" style="font-size:20px;">{{ $account->name }}</h5>
                <p class="bg-warning text-white text-sm truncate rounded -ml-2" style="font-size:11px;">{{ $account->role }}</p>
            </div>
            <span class='text-info -mr-9'>Online</span>
            <div class='bg-light mb-3 p-3 d-flex' style="height: 370px; border: 1px solid #ddd;">
                <div id="chatBox" class="d-flex flex-column justify-content-end" style='width:100%;overflow-y: auto;'>
                    @foreach ($messages as $message)
                        @if ($message->receiver_id == 1 && $message->sender_id == $account->id)
                            <div class="d-flex align-items-center gap-2 mt-2">
                                <img src="{{ $account->profile }}" class="rounded-circle" alt="Profile Image" style="height: 2rem; width: 2rem; object-fit: cover;">
                                <span class='bg-white p-2 rounded-lg'>{{ $message->message }}</span>
                            </div>
                            @php 
                              $re = $message->sender_id;
                            @endphp
                        @elseif ($message->receiver_id == $account->id && $message->sender_id == 1)
                            <div class="text-end mt-4">
                            <span class='bg-info p-2 rounded-lg'>{{ $message->message }}</span>
                          </div>
                          @php 
                              $re = $message->receiver_id;
                            @endphp
                        @endif
                    @endforeach
                </div>
            </div>
            <form method="POST" action="{{ route('admin.chats.store') }}" enctype="multipart/form-data">
    @csrf
            <div class="input-group">
                <input type="text" value='{{ $account->id }}' name='card' hidden>
                <input type="text" value='{{ $re}}' name='receiver_id' hidden>
                <input type="text" id="messageInput" name='message' class="form-control" placeholder="Type a message" aria-label="Message" require>
                <div class="input-group-append">
                    <button class="btn btn-warning" id="sendMessageButton" type="submit" aria-label="Send message">Send</button>
                </div>
            </div>
        </form>

        </div>
    @endif
@endforeach
          <div  class="discussion col-md-8 d-flex justify-content-center align-items-center" style='display:none'>
            <span id='message_toselect'>
              Selecte a chart to start messaging <i class='bx bxs-message-rounded-dots bx-tada text-yellow-300 text-3xl -ml-3' ></i>  
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>




<style>
/* Tablet styles */
@media (max-width: 991px) and (min-width: 768px) {

  .bx-menu{
    display:block;
    font: 3em sans-serif;
  }
}

/* Mobile styles */
@media (max-width: 767px) {
  .bx-menu{
    display:block;
    font: 2em sans-serif;
  }
  .navbar-brand{
    display:none;
  }

}

/* ............. */
.btn-hover-border-bottom {
  position: relative;
  padding-bottom: 2px; /* Add some padding to avoid overlap */
  font-size: 0.875rem; /* Adjust font size for smaller text */
  color: inherit;
  text-decoration: none;
}

.btn-hover-border-bottom::after {
  content: '';
  position: absolute;
  left: 0;
  bottom: 0;
  width: 100%;
  height: 2px;
  background-color: currentColor;
  transform: scaleX(0);
  transform-origin: bottom right;
  transition: transform 0.2s ease-in-out;
}

.btn-hover-border-bottom:hover::after,
.btn-hover-border-bottom:focus::after {
  transform: scaleX(1);
  transform-origin: bottom left;
}

.message-card:focus, .message-card:active {
    background-color: #f0f0f0; /* Background color when card is focused or active */
    color: #333; /* Text color when card is focused or active */
  }
</style>

@if (session('messaged'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
            var cardId = "{{ session('card') }}"; // Retrieve the card ID from session
            var cardElement = document.getElementById(cardId);
            if (cardElement) {
                requestAnimationFrame(function() {
                    cardElement.click();
                    messageModal.show(); // Show the message modal immediately
                });
            } else {
                console.warn("Card element not found:", cardId);
            }
        });
    </script>
@endif


<script>
  // -------------spacific------------
  let showall = document.querySelector('#showall');
  let fixers = document.querySelector('#fixers');
  let customers = document.querySelector('#customers');
  let fixer = document.querySelector('#fixer');
  let customer = document.querySelector('#customer');

  showall.addEventListener('click', function () {
    customer.style.display='block';
    fixer.style.display = 'block';
    fixer.style.animation = 'fadeIn 0.5s ease';
    customer.style.animation = 'fadeIn 0.5s ease';
  });

  fixers.addEventListener('click', function () {
    customer.style.display='none';
    fixer.style.display = 'block';
    fixer.style.animation = 'fadeIn 0.5s ease';
  });

  customers.addEventListener('click', function () {
    fixer.style.display='none';
    customer.style.display = 'block';
    customer.style.animation = 'fadeIn 0.5s ease';
  });

  // -----------end spacific-----------------------
  let message_card  = document.querySelectorAll('.sen');
  let message_toselect  = document.querySelector('#message_toselect');

  for(let sender of message_card) {
    sender.addEventListener('click', function () {
      let discuss = '#sender'+ sender.id;
      message_toselect.style.display="none";
      document.querySelector(discuss).style.display="block";
      message_toselect= document.querySelector(discuss);
      let send = document.querySelector(discuss).children[3].children[1].children[0];
      send.addEventListener('click', function () {
        let messages = document.querySelector(discuss).children[3].children[0];
        if(messages.value!='') {
          let main = document.querySelector(discuss).children[2].children[0];
          let d = document.createElement('div');
          d.classList.add('text-end','mt-4');
          let newMessage = document.createElement('span');
          newMessage.classList.add('bg-info', 'p-2', 'rounded-lg');
          newMessage.style.display = 'inline-block';
          newMessage.style.whiteSpace = 'normal';
          newMessage.textContent = messages.value;
          d.appendChild(newMessage);
          main.appendChild(d);
          messages.value='';
        }
      });
    });
  }
</script>