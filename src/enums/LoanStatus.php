<?php

enum LoanStatus: string {

    case Borrowed = 'borrowed';
    case Returned = 'returned';
    case Overdue = 'overdue';
    case Lost = 'lost';

}