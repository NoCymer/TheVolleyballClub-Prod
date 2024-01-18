<?php

namespace Authorization;

enum AuthorizationLevel: int{
    case Any = 0;
    case Guest = 1;
    case LoggedIn = 2;
    case User = 3;
    case Admin = 4;
}