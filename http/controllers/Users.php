<?php

/**
 * Created by PhpStorm.
 * User: TNS Programmer
 * Date: 2022-02-16
 * Time: 4:39 PM
 */

namespace sprint\http\controllers;

use \sprint\http\core\Controller;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Users extends Controller
{
    private $user;
    private $profile;
    /**
     *
     */
    public function __construct()
    {
        $this->user = new \sprint\models\Users();
        $this->profile = new \sprint\models\Profiles();
        $this->viewsPath = "views/layouts/";
        $_SESSION["activeGroup"] = "admin";
        $_SESSION["activeLink"] = "users";
    }

    /**
     *
     */
    public function index()
    {
        $this->view("Users/Index", []);
    }

    public function show()
    {
        return $this->user->getListOfRegisteredUsers();
    }

    public function create()
    {
        return $this->user->create();
    }

    public function save($id)
    {
        try {
            // return $id;
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $data = $_POST;
                if (!("false" == strtolower($data['deleted_at']))) {
                    unset($data["deleted_at"]);
                }
                $validation = v::key('first_name', v::stringType()->notEmpty()->setName("First Name"))
                    ->key('last_name', v::stringType()->notEmpty()->setName("Last Name"))
                    ->key('birth_date', v::date()->notEmpty()->setName("Birthday"))
                    ->key('email', v::email()->setName("Email address"))
                    ->key('mobile_number', v::digit()->notEmpty()->setName("Mobile Númber"))
                    ->key('province', v::stringType()->notEmpty()->setName("Province"))
                    ->key('district', v::stringType()->notEmpty()->setName("District"))
                    ->key('administrative_post', v::stringType()->setName("Administrative post"))
                    ->key('profile_id', v::notEmpty()->setName("Profile Id"))
                    // ->key('password', v::stringType()->notEmpty()->setName("Password"))                
                    ->assert($data);
                if ($validation == null) {
                    $user = $this->user->single($id);
                    return $this->user->save($id, $data, $user["password"] ?? "");
                }
            }

            $data["user"] = $this->user->single($id);
            $data["profiles"] = $this->profile->show();
            if (empty($data["user"])) return $this->view("Users/Index");

            return $this->view("Users/Save", $data);
        } catch (NestedValidationException | Exception $exception) {
            // echo $exception->getFullMessage();
            $messages = $exception->getMessages() ?? [$exception->getMessage()];
            $message = implode(".<br />", $messages);
            echo json_encode(array(
                "status" => "error",
                "message" => $message
            ));
        }
    }

    public function resetPassword($id)
    {
        try {
            $post = $_POST;
            $message = "The password was successfully reseted";
            $status = "success";
            $user = $this->user->single($id);
            if (empty($user)) {
                $message =  "User invalid,cannot reset password";
                $status  = "error";
            } else {
                // $password = $this->user->getEncryptedPassword($user["mobile_number"]);
                $password = $user["mobile_number"];
                if (empty($password)) {
                    $message = "Error occured: was unable to generate the encrypted password for reset!";
                    $status = "error";
                } else {
                    $reset = $this->user->resetPassword($user["id"], $password);
                    #If it faied toupdate
                    if (!$reset) {
                        $message = "Error occured: was unable to reset password!";
                        $status = "error";
                    }
                }
            }
            echo json_encode(array(
                "message" => $message,
                "status"  =>  $status
            ));
        } catch (NestedValidationException | Exception $exception) {
            // echo $exception->getFullMessage();
            $messages = $exception->getMessages() ?? [$exception->getMessage()];
            $message = implode(".<br />", $messages);
            echo json_encode(array(
                "status" => "error",
                "message" => $message
            ));
        }
    }


    public function sendPasswordLink()
    {
        try {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $data = $_POST;
                $validation = v::key('email', v::email()->setName("Email address"))
                    ->assert($data);
                if ($validation == null) { #Check if the user typed the right password
                    $user = $this->user->findByEmail($data["email"]);
                    $email = $data["email"];

                    $token = bin2hex(random_bytes(16));

                    $token_hash = hash("sha256", $token);

                    $expiry = date("Y-m-d H:i:s", time() + 60 * 15);

                    if ($user != null) {
                        $afected_rows = $this->user->updateTokenForPasswordReset($token_hash, $expiry, $email, $user["id"]);

                        $mail = new PHPMailer(true);
                        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                        $mail->isSMTP();
                        $mail->Host       = 'smtp.gmail.com';
                        $mail->SMTPAuth   = true;
                        $mail->Username   = 'tns@tnslabs.org';
                        $mail->Password   = 'rywgrngolcnyzeuv';
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                        $mail->Port       = 465;
                        //Recipients
                        $mail->setFrom('tns@tnslabs.org', 'No Replay');
                        $mail->addAddress($email, $user["first_name"]." ".$user["last_name"]);
                        $mail->isHTML(true);
                        $mail->Subject = 'Password reset';
                        $rota = route("/password-reset");
                        $mail->Body = <<<END
                                            Hello <b>{$user['first_name']} {$user['last_name']}</b>!<br>Click <a href="{$rota}/{$token}">here</a> 
                                            to reset your password.<br>
                                            The password expires in 15 minutes!<br>
                                            <span style="color:red"><strong>Please, do not share this email with others.</strong></span>
                                            END;
                        $mail->send();
                        return json_encode(array(
                            "status" => "success",
                            "message" => "Password reset link sent to your email!"
                        ));
                        exit();
                    } else {
                        echo json_encode(array(
                            "status" => "info",
                            "message" => "Invalid email address!"
                        ));
                        exit();
                    }
                } else {
                    echo json_encode(array(
                        "status" => "info",
                        "message" => "Validation error. Please, check the fields!"
                    ));
                    exit();
                }
            } else {
                echo json_encode(array(
                    "status" => "info",
                    "message" => "Use the proper form to request a password link."
                ));
                exit();
            }
        } catch (NestedValidationException | Exception $exception) {
            // echo $exception->getFullMessage();
            $messages = $exception->getMessages() ?? [$exception->getMessage()];
            $message = implode(".<br />", $messages);
            echo json_encode(array(
                "status" => "error",
                "message" => $message
            ));
            exit();
        }
    }

    public function passwordResetForm($token)
    {
        try {
            $data["token"] = $token;
            return $this->view("Auth/new-password", $data);
        } catch (\Exception $exception) {
            $messages =  [$exception->getMessage()];
            $message = implode(".<br />", $messages);
            echo json_encode(array(
                "status" => "error",
                "message" => $message
            ));
            exit();
        }
    }

    public function updateUserPassword()
    {
        try {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $data = $_POST;
                
                $token = $data["token"];

                $token_hash = hash("sha256", $token);

                $user = $this->user->findByToken($token_hash);

                if ($user === null) {
                    echo json_encode(array(
                        "status" => "info",
                        "message" => "Link not found!"
                    )); exit();
                }

                if (strtotime($user["reset_token_expires_at"]) <= time()) {
                    echo json_encode(array(
                        "status" => "info",
                        "message" => "Link has expired!"
                    ));exit();
                }

                if (strlen($_POST["password"]) < 8 || strlen($_POST["confirm_password"]) < 8) {
                    echo json_encode(array(
                        "status" => "info",
                        "message" => "Password must be at least 8 characters!"
                    ));exit();
                }


                if ($_POST["password"] != $_POST["confirm_password"]) {
                    echo json_encode(array(
                        "status" => "info",
                        "message" => "Password dont match!"
                    ));exit();
                }
                // if (!preg_match("/[a-z]/i", $_POST["password"])) {
                //     die("Password must contain at least one letter");
                // }

                // if (!preg_match("/[0-9]/", $_POST["password"])) {
                //     die("Password must contain at least one number");
                // }

                // if ($_POST["password"] !== $_POST["password_confirmation"]) {
                //     die("Passwords must match");
                // }

                // $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
                $newPassword = $_POST["password"];

                $result = $this->user->update("users")->values(["password" => $newPassword, "reset_token_expires_at" => "1999-01-01 00:00:00"])->where("id = {$user['id']}");
                if ($result) {
                    echo json_encode(array(
                        "status" => "success",
                        "message" => "Password updated. You can now login!"
                    ));exit();
                } else {
                    echo json_encode(array(
                        "status" => "info",
                        "message" => "Failed to update the password. Try again!"
                    )); exit();
                }
            }
        } catch (NestedValidationException | Exception $exception) {
            // echo $exception->getFullMessage();
            $messages = $exception->getMessages() ?? [$exception->getMessage()];
            $message = implode(".<br />", $messages);
            echo json_encode(array(
                "status" => "error",
                "message" => $message
            ));
            exit();
        }
    }
}
