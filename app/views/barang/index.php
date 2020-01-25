<div class="main" style=" margin-left: 200px">

<div class="row">
	<div class="container">
    <h1>Daftar Barang</h1>
		<div style="height:5px;"></div>
		<table class="table table-striped table-hover table-bordered">
			<thead>
              <tr>
                <th class="text-center" scope="col">ID</th>
                <th scope="col">Kode Barang</th>
                <th scope="col">Nama Barang</th>
              </tr>
            </thead>
			<tbody>
                <?php 
                foreach($data['barang'] as $barang):?>
                    <tr>
                    <td class="text-center"><?= $barang['0']; ?></td>
                    <td><?= $barang['1']; ?></td>
                    <td><?= $barang['1']; ?></td>
                    </tr>
                <?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
</div>