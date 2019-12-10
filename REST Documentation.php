<?php

/**
 * |---------------------------------------------
 * |  Documentation for AmVirgin backend API's
 * |---------------------------------------------
 */

/**
 * ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
 * Seller Authentication Routes
 * ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
 * ----------------------------------------------------------------------------------------------------------------------------------------------------------------
 * Route -> seller?mobile={mobile} or seller?email={email}
 * Method -> GET
 * Header -> [Accept: application/json]
 * Description -> Check existence of a seller through mobile or email. Consider this an essential step before letting them to register or log in.
 * Parameters ->
 * Notes -> You can use either of mobile or email to find if a seller already exists, just be sure to use the corresponding query parameter for data.
 * ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
 * ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
 * Route -> seller/login
 * Method -> POST
 * Header -> [Accept: application/json]
 * Description -> Attempts to log
 * Parameters -> {email: (email or null), mobile: (mobile or null), password: (password)}
 * Notes -> Either of email or mobile is required to log in, if one is unavailable, be sure to make other one mandatory
 * ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
 */

//$model->name
