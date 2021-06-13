<nav class="navbar notification is-success p-0" role="navigation" aria-label="main navigation">
  <div class="navbar-brand">
    <a class="navbar-item has-text-weight-bold" href="./" style=" text-decoration: none;" title="VSN">
      <img src="./assets/images/favicon.ico" alt="VSN" class="mr-2" style="max-height: 2rem;">VSN
    </a>

    <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
    </a>
  </div>
  <div id="navbarBasicExample" class="navbar-menu notification is-success p-0">

    <div class="navbar-end">
      <div class="navbar-item">
        <div class="buttons">

          <div class="dropdown mr-2 mb-2">
            <div class="dropdown-trigger">
              <div class="field" title="Friend Requests">
                <a class="control is-expanded has-icons-right" id="friend_request_area">
                  <i class="fa fa-user-plus fa-2" aria-hidden="true"></i>
                  <span class="caret" style="display: inline-block; width: 0; height: 0; margin-left: 2px; vertical-align: middle; border-top: 4px dashed; border-top: 4px solid\9; border-right: 4px solid transparent; border-left: 4px solid transparent;"></span>
                </a>
              </div>
            </div>
            <div class="dropdown-menu" id="dropdown-menu" role="menu">
              <div class="dropdown-content">
                <?php
                $ctr = 0;
                foreach ($friend_request_data as $key => $friend) {
                  $ctr++;
                ?>
                  <span class="dropdown-item has-text-success has-text-weight-bold" style="width: max-content;">Requests</span>
                  <hr class="dropdown-divider" style="background:#00d1b2; height: 3px;">
                  <div class="dropdown-item has-text-success has-text-weight-bold" style="width: max-content;">
                    <div class="list-group-item list-group-action">
                      <div class="media">
                        <div class="chat-user-img  align-self-center mr-3">
                          <img src="<?php echo $friend['user_profile']; ?>" ; class="rounded-circle avatar-xs" alt="">
                        </div>
                        <div class="media-body overflow-hidden" style="display:flex;">
                          <h5 class="text-truncate font-size-15 mb-1 has-text-weight-bold" style="align-self: center;"><?php echo $friend['user_name']; ?></h5>
                          <span class="ml-4"><a class="button is-warning p-1 m-1" href="AcceptFriendRequest.php/?id=<?php echo $friend['user_id']; ?>"><i class="fa fa-check"></i></a></span>
                        </div>
                      </div>

                    </div>
                  </div>
                <?php
                }
                if ($ctr == 0) {
                ?>
                  <span class="dropdown-item has-text-success has-text-weight-bold" style="width: max-content;">You Have No Requests</span>
                  <hr class="dropdown-divider" style="background:#00d1b2; height: 3px;">

                <?php

                }
                ?>
              </div>
            </div>
          </div>


          <a class="image mr-2" href="#" style="width: 30px; height: 40px;" title="Profile">
            <img class="is-rounded" src="<?php echo $user_data['user_profile']; ?>" alt="Profile">
          </a>
          <form method="post" action="logout.php">
            <button class="button is-dark is-rounded" title="Logout" name="logout">
              <i class="fa fa-sign-out mr-2"></i>
              Logout
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</nav>