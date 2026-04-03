<?php
/**
 * Template Name: Custom Registration Page
 * This template allows users to register on the front end of InPursuitofFlavah
 */

// If a user is already logged in, we don't need them signing up again!
if ( is_user_logged_in() ) {
    wp_redirect( home_url() );
    exit;
}

$errors = array();

// Check if the user just clicked the register button
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty( $_POST['action'] ) && $_POST['action'] == 'custom_register_user' ) {

    // Sanitize the inputs to keep things clean and secure
    $username = sanitize_user( $_POST['username'] );
    $email    = sanitize_email( $_POST['email'] );
    $password = $_POST['password'];
    
    // Quick validation checks
    if ( empty( $username ) || empty( $email ) || empty( $password ) ) {
        $errors[] = 'Please fill out all the fields!';
    }
    
    if ( username_exists( $username ) ) {
        $errors[] = 'Ah, that username is already taken. Try another one!';
    }
    
    if ( ! is_email( $email ) ) {
        $errors[] = 'That doesn\'t look like a valid email address.';
    } elseif ( email_exists( $email ) ) {
        $errors[] = 'An account with that email already exists!';
    }

    // If everything looks good, let's create the user!
    if ( empty( $errors ) ) {
        $user_id = wp_create_user( $username, $password, $email );
        
        if ( ! is_wp_error( $user_id ) ) {
            // Force the new user to be a 'Customer' (Req #3)
            $user = new WP_User( $user_id );
            $user->set_role( 'customer' );
            
            // Log them in automatically right after they register
            wp_set_current_user( $user_id );
            wp_set_auth_cookie( $user_id );
            
            // Send them to the homepage to start exploring
            wp_redirect( home_url() );
            exit;
        } else {
            $errors[] = 'Something went wrong creating the account. Please try again.';
        }
    }
}

get_header(); ?>

<div class="registration-container" style="max-width: 500px; margin: 50px auto; padding: 30px; background: #fff; border: 1px solid #eadecc; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05);">
    
    <h2 style="font-family: 'Playfair Display', serif; text-align: center; color: #ff5722; margin-bottom: 20px;">Join In Pursuit of Flavah</h2>

    <?php 
    // Show errors if they messed up filling out the form
    if ( ! empty( $errors ) ) {
        echo '<div style="background: #ffebee; color: #c62828; padding: 10px; border-radius: 6px; margin-bottom: 20px;">';
        foreach ( $errors as $error ) {
            echo '<p style="margin: 0;">' . esc_html( $error ) . '</p>';
        }
        echo '</div>';
    }
    ?>

    <form method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
        
        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Username</label>
            <input type="text" name="username" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;" required>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Email</label>
            <input type="email" name="email" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;" required>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Password</label>
            <input type="password" name="password" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;" required>
        </div>

        <input type="hidden" name="action" value="custom_register_user">
        
        <button type="submit" style="width: 100%; padding: 12px; background: #ff5722; color: #fff; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">Sign Up</button>
    </form>
    
    <p style="text-align: center; margin-top: 15px;">Already have an account? <a href="<?php echo wp_login_url(); ?>" style="color: #ff5722; font-weight: bold;">Log in here</a></p>
</div>

<?php get_footer(); ?>