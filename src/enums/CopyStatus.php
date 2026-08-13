<?php

enum CopyStatus: string {

    case Available = 'available';
    case Unavailable = 'unavailable';
    case Borrowed = 'borrowed';
    case Reserved = 'reserved';

}