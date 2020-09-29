<?php
if( !empty( $_REQUEST['Message'] ) )
{
    echo sprintf( '<p class="output_message">%s</p>', $_REQUEST['Message'] );
}