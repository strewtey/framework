<?php
Enter::route(['make:component {name}', 'make:component {name} {more?}'], [\Scraps\Component\Generate\Controllers\ComponentController::class, 'index']);
Enter::route(['remove:component {name}', 'remove:component {name} {more?}'], [\Scraps\Component\Generate\Controllers\ComponentController::class, 'remove']);
Enter::route(['make:layout {path}', 'make:layout {path} {more?}'], [\Scraps\Component\Generate\Controllers\LayoutController::class, 'index']);
Enter::route(['remove:layout {path}', 'remove:layout {path} {more?}'], [\Scraps\Component\Generate\Controllers\LayoutController::class, 'remove']);
Enter::route(['serve', 'serve {more?}'], [\Scraps\Servers\Server::class, 'init', true]);
