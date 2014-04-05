<?php

/**
 * Gets the associated character of athe current user or a specified user id
 * Returns null if no character is found
 *
 * @param type $id
 * @return null
 */
function get_the_character( $id = false ) {
    if ( false == $id ) {
        return PlayfulCharacters::get_instance()->get_current_character();
    } else {
        //TODO
        return null;
    }
}

/**
 * Returns true if the current, or a specified user has a character set up
 * False if not
 *
 * @param type $id
 * @return boolean
 */
function has_character( $id = false ) {
    if ( false == $id ) {
        return (NULL !== get_the_character( $id ));
    } else {
        //TODO
        return false;
    }
}
