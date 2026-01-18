<!DOCTYPE html>
<html>
<head>
    <title>Menu Example</title>
    <style>
        .navbar-nav { list-style: none; display: flex; }
        .navbar-nav li { margin-right: 20px; }
        .navbar-nav a { text-decoration: none; color: #333; }
        .navbar-nav a:hover { color: #007bff; }
        
        .admin-menu { list-style: none; }
        .admin-menu li { margin-bottom: 10px; }
        .admin-menu a { text-decoration: none; color: #333; }
        .submenu { margin-left: 20px; list-style: none; }
        .submenu li { margin-bottom: 5px; }
    </style>
</head>
<body>
    <h1>Menu Examples</h1>
    
    <h2>Main Navigation Menu:</h2>
    {!! Menu::render('main') !!}
    
    <h2>Admin Menu:</h2>
    {!! Menu::render('admin') !!}
    
    <h2>Custom Menu with Blade View:</h2>
    {!! Menu::render('main', 'menu.default') !!}
</body>
</html>













