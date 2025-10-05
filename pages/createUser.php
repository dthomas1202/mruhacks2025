<!DOCTYPE html>
<html lang="en">
<head>
<?php require("static/head.php");?>
<link rel="stylesheet" href="css/newuser.css">
</head>
<body>
    <h1>FriendLink</h1>
    <p>
    Create a new account
    </p>

    <form action="api/createUser.php" method="POST">
        <label for="name">Name: </label>
        <input required type="text" id="name" name="name" placeholder="Your Name"> <br>
        <label for="email"> Email: </label> 
        <input required type="text" id="email" name="email" placeholder="you@example.com"><br>
        <label for="password" id="password">Password: </label>
        <input required type="text" id="password" name="password" placeholder="Password"> <br>
        <label for="subjects" id="subjects">Subjects: </label>
        <textarea required id="subjects" name="subjects" placeholder="Math, Web, English..." ></textarea> <br>
        <label for="skills" id="skills">Skills: </label>
        <textarea required id="skills" name="skills" placeholder="Development, Drawing..."></textarea> <br>

        <div class="traffic">
            <lable>Traffic Light</lable>
            <div class="lights">
                <div class="greenLight">
                    <input required type="radio" value="2" name="traffic" onclick="setFocusDisplay(true, false, false);">
                    <span class="light" id="green"></span>
                </div>
                <div class="yellowLight">
                    <input required type="radio" value="1" id="yellow" name="traffic" onclick="setFocusDisplay(false, true, false);">
                    <span class="light"></span>
                </div>
                <div class="redLight">
                    <input required type="radio" value="0" id="red" name="traffic" onclick="setFocusDisplay(false, false, true);">
                    <span class="light"></span>
                </div>
            </div>
            <p class="status" id="noneSelected">Select level of focus</p>
            <label class="status" id="focusG" for="green">"Yap Session"</label>
            <label class="status" id="focusY" for="yellow">"Chat and work"</label>
            <label class="status" id="focusR" for="red">"I need to LOCK IN"</label>
        </div>

        <label for="time">Preferred Time: </label> 
        <input required type="text" id="time" name="time"> <br>
        <input type="submit" value="Submit"> 
    </form>

    
</body>
<script>
function setFocusDisplay(g, y, r) {
    document.getElementById("focusG").style.display = g ? "inline" : "none"; 
    document.getElementById("focusY").style.display = y ? "inline" : "none"; 
    document.getElementById("focusR").style.display = r ? "inline" : "none"; 
}

setFocusDisplay(false, false, false)

</script>
</html>
