<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>

    *{
        box-sizing: border-box;
        margin: 0; padding: 0;
    }

    body{
        background-color: #f7f7f7;
        font-family: Arial, sans-serif;
        font-size: 16px;
    }

    table{
        border-collapse: collapse;
        margin: 20px auto;
        width: 40%;
    }    

    th{
        text-align: left;
        background-color: #333;
        color: #fff;
        padding: 12px;
    }

    td{ padding: 12px }

    .out-of-stock{ background-color : #f44336; color: #fff; }

    .high-stock{ background-color: #8bc34a; color: #fff; }

    .medium-stock{ background-color: #ffa726; color: #fff; }

    .low-stock{ background-color: #ff7043; color: #fff; }
    
</style>
<main id="main">

<!-- ======= Services Section ======= -->
<section id="services" class="services">
  <div class="container">

    <div class="section-title">
      <h2>Notaris</h2>
      <p>Kepatuhan Pengumpulan Laporan Notaris berdasarkan Tanggal Pengumpulan</p>
    </div>
    <ul class="breadcrumb"><li><a class="homeLink" href="<?php echo base_url().'home/index'?>"><i class="icofont-home"></i> Beranda /</a></li>
      <li class="active">Daftar Notaris </li>
    </ul>
    
    <!-- <div>
        <canvas id="myChart"></canvas>
    </div> -->

      <!-- NOTARIS -->
    <div class="row">
      <div class="col-lg-8 mt-8 mt-lg-0 ">
        <div class="row"> 
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="notaris">
                            <thead>
                              <tr>
                                  <th>Nama Notaris</th>
                                  <th>Januari</th>
                                  <th>Februari</th>
                                  <th>Maret</th>
                                  <th>April</th>
                                  <th>Mei</th>
                                  <th>Juni</th>
                                  <th>Juli</th>
                                  <th>Agustus</th>
                                  <th>September</th>
                                  <th>Oktober</th>
                                  <th>November</th>
                                  <th>Desember</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php foreach ($notaris as $notaris) : ?>
                              <tr class="laporan">
                                  <td><?php echo $notaris['nama_notaris']; ?></td>
                                  <td class="bulanini"><?php echo $notaris['Januari']; ?></td>
                                  <td class="bulan"><?php echo $notaris['Februari']; ?></td>
                                  <td class="bulan"><?php echo $notaris['Maret']; ?></td>
                                  <td class="bulan"><?php echo $notaris['April']; ?></td>
                                  <td class="bulan"><?php echo $notaris['Mei']; ?></td>
                                  <td class="bulan"><?php echo $notaris['Juni']; ?></td>
                                  <td class="bulan"><?php echo $notaris['Juli']; ?></td>
                                  <td class="bulan"><?php echo $notaris['Agustus']; ?></td>
                                  <td class="bulan"><?php echo $notaris['September']; ?></td>
                                  <td class="bulan"><?php echo $notaris['Oktober']; ?></td>
                                  <td class="bulan"><?php echo $notaris['November']; ?></td>
                                  <td class="bulan"><?php echo $notaris['Desember']; ?></td>
                              </tr>
                              <?php endforeach ;?>
                            </tbody>
                            </table>
                            <script>

// Select all elements with the class "product-row"
const rows = document.querySelectorAll(".laporan");

// Loop through each "product-row" element
for(let i = 0; i < rows.length; i++){

    // Get the stock value of the current product
const bulan = rows[i].querySelector(".bulanini").textContent;

// Add a class to the current element based on stock value
    // Product is out of stock	
    if(bulan == 0){ 
        rows[i].classList.add("out-of-stock");
      } 

     // Product has low stock
    else if(bulan > 0 && bulan < 11){ 
        rows[i].classList.add("high-stock"); 
      }

    // Product has medium stock
    else if(bulan >= 11 && bulan < 20){ 
        rows[i].classList.add("medium-stock");
       } 
    
     // Product has high stock
    else { rows[i].classList.add("high-stock"); }

}

</script>
<script>
  const ctx = document.getElementById('myChart');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Pengda Kendari Patuh', 'Pengda Kendari Tidak Patuh', 'Pengda Baubau Patuh', 'Pengda Baubau Tidak Patuh', 'Pengda Kolaka Patuh', 'Pengda Kolaka Tidak Patuh'],
      datasets: [{
        label: '# Kepatuhan per Pengda',
        data: [68, 58, 24, 21, 17, 16],
        backgroundColor: [
      'rgba(75, 192, 192, 0.2)',
      'rgba(255, 99, 132, 0.2)'

    ],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>
                        </div>
                        <!-- /.card-body -->
        </div>
        
      </div>
    </div>
  </div>
</section><!-- End Services Section -->
    </main><!-- End #main -->            </div>
