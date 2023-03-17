<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meteo a Velletri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="css/stile.css">
</head>
<body>
    <div class="container-xxl bordo">
        <header id="mainHeader" class="mt-5 mb-5 mt-lg-4 mb-lg-4 position-relative">
            <form id="loginForm" class="loginForm" action="index.php" method="POST">
                <div class="formRow">
                  <label class="inlineBlockLabel mb-2" for="email">Email: </label>
                  <input class="inputForm" id="email" type="email" name="email" placeholder="Insert your email address" required>
                </div>
                <div class="formRow">
                  <label class="inlineBlockLabel mb-2" for="password">Password: </label>
                  <input class="inputForm" id="password" type="password" name="password" placeholder="Insert your password" required>
                </div>
                <input class="submitRight" type="submit" value="Login">
            </form>
            <h1 id="mainTitle" class="pt-3 pb-3">
                Meteo a Velletri
            </h1>            
        </header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                  <li class="nav-item me-3">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                  </li>
                  <li class="nav-item me-3">
                    <a class="nav-link" href="#">Storico</a>
                  </li>
                  <li class="nav-item me-3">
                    <a class="nav-link" href="#">Analisi Dati</a>
                  </li>
                  <li class="nav-item me-3">
                    <a class="nav-link" href="#">Chi siamo?</a>
                  </li>
                </ul>
                
              </div>
            </div>
          </nav>

          <?php 
            require_once "controller/funzioni.php";
            
            printRow(getLast($connection)[0]);
            
            $sql = "SELECT * FROM rilevazioni2023";
            $result = query($connection,$sql);
            printData($result);
          ?>
    </div>    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>