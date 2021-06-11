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
                foreach ($friend_request_data as $key => $friend) {
                ?>
                  <div class="panel-block">
                    <article class="media">
                      <figure class="media-left">
                        <p class="image is-48x48 m-2">
                          <img class="is-rounded" src="<?php echo $friend['user_profile']; ?>">
                        </p>
                      </figure>
                      <div class="media-content">
                        <div class="content has-text-dark">
                          <p style="display: flex;" class="m-2">
                            <strong><?php echo $friend['user_name']; ?></strong>
                            <a class="button is-primary is-rounded mx-2" href="AcceptFriendRequest.php/?id=<?php echo $friend['user_id']; ?>">Accept</a>
                          </p>
                        </div>
                      </div>
                    </article>
                  </div>
                <?php
                }
                ?>
              </div>
            </div>
          </div>


          <a class="image mr-2" href="#" style="width: 30px; height: 40px;" title="Profile">
            <img class="is-rounded" src="<?php echo $user_data['user_profile']; ?>" alt="Profile">
          </a>
          <a class="button is-dark is-rounded" href="logout.php" title="Logout">
            <i class="fa fa-sign-out mr-2"></i>
            Logout
          </a>
        </div>
      </div>
    </div>
  </div>
</nav>