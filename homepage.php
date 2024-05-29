<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>YUPI - UP Mindanao Library Log</title>
    <style>
      /* General Styles */
      body {
          font-family: Arial, sans-serif;
          background-color: #F5AB29;
          color: #333;
          margin: 0;
          padding: 0;
          overflow: hidden; /* Prevent scrolling */
          background-image: url('upmlibrary.png'); 
          background-size: cover;
          overflow-x: hidden;
      }
            

      header {
          background-color: #00573F;
          color: white;
          display: flex;
          align-items: center;
          justify-content: space-between;
          padding: 10px 20px;
      }

      .header-text {
          display: flex;
          align-items: center;
      }

      .logo-container {
          width: 80px; /* Adjust the width of the logo container */
          height: 80px; /* Adjust the height of the logo container */
          background-image: url('yupilogo.png'); /* Path to your logo image */
          background-size: cover;
          background-repeat: no-repeat;
          margin-right: 10px; /* Adjust spacing between logo and text */
      }

      .header-title {
          display: flex;
          flex-direction: column;
      }

      header h1 {
          font-family: 'Quiapo', sans-serif;
          font-size: 53px;
          margin: 0;
          text-align: left;
      }

      header h5 {
          font-family: 'OpenSans', sans-serif;
          font-size: 12px;
          margin: 0;
          text-align: left;
      }

      header button {
          background-color: #F5AB29;
          color: rgb(19, 18, 18);
          font-family: 'OpenSans', sans-serif;
          border: none;
          padding: 10px 75px;
          cursor: pointer;
          border-radius: 50px;
          margin-right: 18px;
          transition: background-color 0.2s ease;
      }

      header button:hover {
          background-color: hsl(357, 79%, 15%);
          color: white;
          box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
      }

      /* Main Content */
      h3, h1, h2 {
          text-align: center;
      }

      .bungad h3 {
          color: white;
          font-family: 'Quiapo', sans-serif;
          font-size: 125px;
          line-height: 0;
          margin-left: -200px;
          text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
      }

      .bungad h1 {
          color: white;
          font-family: 'Quiapo', sans-serif;
          font-size: 400px;
          position: relative;
          line-height: 0;
          margin-left: -200px;
          z-index: -2;
          text-shadow: 2px 2px 19px rgba(0, 0, 0, 0.5);
      }

      .oblation-img {
          position: absolute;
          top: 30px;
          right: 10px;
          z-index: -1;
          width: 1000px; /* Adjust the width as needed */
          
      }

      .fold {
          position: absolute;
          top: -235px;
          right: 0px;
          z-index: -1;
          width: 1000px; /* Adjust the width as needed */
          height: auto; /* Automatically adjust the height to maintain aspect ratio */
      }

      .selection_Button {
            display: inline-block;
            flex-direction: column;
            align-items: center;
            margin-top: 170px;
            margin-left: 170px;

        }
       
        .button {
            text-decoration: none;
            padding: 1.5em 5em;
            border: none;
            border-radius: 25px;;
            color: #dfd0d0;
            font-size: 2.0rem;
            font-weight: 800px;
            letter-spacing: .2rem;
            text-align: center;
            outline: none;
            cursor: pointer;
            transition: .2s ease-in-out;
            border-radius: 25px;
            box-shadow: -5px -5px 10px rgb(238, 228, 227), 5px 5px 10px rgba(0, 0, 0, 0.485);
            margin: 20px;
            
        }

        .button-user {
            background-color: maroon; /* UP Green */
            margin-left: 20px;
        }

        .button-admin {
            background-color: #00573F; /* UP Maroon */
            margin-left:-100px;
        }

        .button:hover {
            box-shadow: -2px -2px 6px rgba(255, 255, 255, .6),
                        -2px -2px 4px rgba(255, 255, 255, .4),
                        2px 2px 2px rgba(255, 255, 255, .05),
                        2px 2px 4px rgba(0, 0, 0, .1);
        }

        .button:active {
            box-shadow: inset -2px -2px 6px rgba(255, 255, 255, .7),
                        inset -2px -2px 4px rgba(255, 255, 255, .5),
                        inset 2px 2px 2px rgba(255, 255, 255, .075),
                        inset 2px 2px 4px rgba(0, 0, 0, .15);
        }



      @font-face {
          font-family: 'Quiapo';
          src: url('Quiapo_Free.ttf') format('truetype');
          /* Add additional formats if necessary */
          font-weight: normal;
          font-style: normal;
      }

      @font-face {
          font-family: 'OpenSans';
          src: url('OpenSans-VariableFont_wdth\,wght.ttf') format('truetype'),
              url('OpenSans-Italic-VariableFont_wdth\,wght.ttf') format('truetype');
          font-weight: normal;
          font-style: normal;
      }

    </style>
</head>
<body>
    <header>
        <div class="header-text">
            <a href="index.html">
                <div class="logo-container"></div>
            </a>
            <div class="header-title">
                <h1>YUPI</h1>
                <h5>UP Mindanao Library Log</h5>
            </div>
        </div>
        
    </header>
    
    <div class="bungad">
    

        <img src="Oble.png" alt="Oblations" class="oblation-img">
        <img src="fold.png" alt="Fold" class="fold">
    </div>

    <div class="selection_Button">
        
        <a href="index.html" class="button" style="background-color: #7B1113;">User</a> <br><br><br><br><br><br><br><br><br><br><br><br>
        <a href="admin_loginlandingpage.html" class="button" style="background-color: #3c9165;">Admin</a>
    </div>
        
</body>
</html>
