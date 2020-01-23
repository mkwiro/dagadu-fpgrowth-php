 <div class="row justify-content-center p-5">
    <div class="col-xl-5 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
        	<div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            	<div class="row">
            		<div class="col-lg-12">
                		<div class="p-5">
		                  <div class="text-center">
		                    <h1 class="h3 text-gray-900 mb-4">Selamat Datang di <br> Toko Akhsan Mart</h1> 
		                  </div>

		                <form class="form-signin" role="form" action="<?php echo $urlaplikasi;?>login.php" method="post" class="user">
		                  	<div class="form-group">
		                      <input name="username" type="" class="form-control form-control-user" placeholder="Masukan Username..." required autofocus>
		                    </div>
		                    <div class="form-group">
		                      <input name="password" type="password" class="form-control form-control-user" placeholder="Masukan Password..." required>
		                    </div>

		                     <button type="submit" class="btn btn-primary form-control" name="submit">Login</button>
                    		<hr>
                    	</form>
                    		<div class="text-center">
		                    <a class="small" href="<?php echo $urlaplikasi;?>index.php?=registrasi">Belum Punya Akun ? Klik Disini!</a>
		                  </div>
		                </div>
					</div>
            	</div>
          	</div>
        </div>
    </div>
</div>