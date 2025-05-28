import React, { useState, useEffect } from 'react';
import { StyleSheet, Pressable, Text, View } from 'react-native';
import { Link, useNavigation } from 'expo-router';
import { getAuth, signInWithEmailAndPassword } from 'firebase/auth';
import Button from '@/components/Button';
import TextField from '@/components/TextField';
import Password from '@/components/Password';
import ConnectSocial from '@/components/SocialNetwork';
import { useRouter } from 'expo-router';
import { Color, FontSize, Padding } from "@/globals/Styles";
import SignedUpSuccessfully from "@/auth/modals/SignedUp"
import useTranslation from '@/hooks/useTranslation';
import Checkbox from "@/components/Checkbox1"
import Line from '@/components/line';

const SignIn = () => {

  const router = useRouter();
  const navigation = useNavigation();
  const { translate, isReady } = useTranslation();
  const [usernameValue, setUsernameValue] = useState('obarrientos@hotmail.com');
  const [passwordValue, setPasswordValue] = useState('Zxcvb123!');
  const [errorMessage, setErrorMessage] = useState('');
  const [modalVisible, setModalVisible] = useState(false); // State to control modal visibility
  const [isChecked, setChecked] = useState(false);
  const [error, setError] = useState('');

  useEffect(() => {
    if (isReady) { // Ensure translations are loaded
      navigation.setOptions({
        headerShown: true,
        title: translate("signin"),
        headerBackVisible: false,
      });
    }
  }, [navigation, isReady, translate]);

  const handleSignIn = () => {

    const auth = getAuth();

    signInWithEmailAndPassword(auth, usernameValue, passwordValue)
      .then((userCredential) => {
        // Handle successful sign in
        // console.log(auth);
        setModalVisible(true);
        setTimeout(() => {
          setModalVisible(false); // Close the modal just before navigating
          //router.replace('../screens/auth/ProfileScreen'); // Adjust with your actual navigation call
        }, 2000);
        router.replace('../screens/Index');
        // const user = userCredential.user;
      })
      .catch((error) => {
        // Handle errors here
        setErrorMessage(error.message);
        // Hide the error message after 5 seconds
        setTimeout(() => {
          setErrorMessage('');
        }, 5000);
        // const errorCode = error.code;
        // const errorMessage = error.message;
      });
  };

  return (
    <View style={styles.container}>
      {errorMessage !== '' && (
        <View style={styles.errorBar}>
          <Text style={styles.errorText}>{errorMessage}</Text>
        </View>
      )}
      <Text style={styles.title}>{translate("welcome")}</Text>
      <Text style={styles.subTitle}>{translate("credentials")}</Text>
      <TextField
        translationKey="email" // Assume "email" is the key in your translations for the email field
        value={usernameValue}
        onChange={setUsernameValue}
        errorTranslationKey={error} // Assuming error keys are also translated
      />

      <Password
        translationKey="email" // Assume "email" is the key in your translations for the email field
        value={passwordValue}
        onChange={setPasswordValue}
        errorTranslationKey={error} // Assuming error keys are also translated
      />

      <Line />

      <View style={styles.rememberMeSection} >
        <Checkbox setChecked={setChecked} isChecked={isChecked} />
        <Text style={styles.rememberMe}>{translate("rememberme")}</Text>
      </View>

      <View style={styles.forgotPasswordSection} >
        <Pressable onPress={() => { }}>
          <Text style={styles.forgotPassword}>{translate("forgotpassword")}</Text>
        </Pressable>

        <Pressable style={styles.dontHaveAnAccountPressable} onPress={() => { }}>
          <Text style={styles.dontHaveAnAccountText}>{translate("donthaveaccount")}</Text>
          <Link href="./SignUp"><Text style={styles.signUp}>{translate("signup")}</Text></Link>
        </Pressable>


      </View>



      <View style={styles.buttonPosition}>

        <View style={styles.socialnetworks}>
          <ConnectSocial />
        </View>

        <Button onPress={handleSignIn} disabled={false} invertColors={false} >
          {translate("login")}
        </Button>
      </View>
      <SignedUpSuccessfully modalVisible={modalVisible} setModalVisible={() => setModalVisible(!modalVisible)} />
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: Color.white,
    padding: Padding.p_base,
  },
  title: {
    fontSize: 22,
    fontWeight: 'bold',
    marginVertical: 8,
  },
  subTitle: {
    fontSize: 18,
    marginVertical: 8,
    marginBottom: 40,
  },

  forgotPasswordSection: {
    top: 10,
  },
  dontHaveAnAccountText: {
    lineHeight: 26,
    fontSize: FontSize.bodyLarge,
    textAlign: "center",
    color: Color.greyscale900,
    marginRight: 15,
    fontFamily: 'Urbanist-SemiBold',
    fontWeight: "500",
  },
  dontHaveAnAccountPressable: {
    top: 20,
    width: 350,
    alignItems: "center",
    flexDirection: "row",
    justifyContent: "center",
    position: "relative",
  },
  iconLayout: {
    width: 24,
    height: 24,
  },


  inputSection: {
    position: "relative",
  },


  rememberMeSection: {
    alignItems: "center",
    flexDirection: "row",
    position: "relative",
  },
  rememberMe: {
    fontSize: FontSize.bodyLarge,
    color: Color.greyscale900,
    flex: 1,
    fontFamily: 'Urbanist-SemiBold',
    fontWeight: "600",
    letterSpacing: 0,
    textAlign: "left",
  },
  lineLayout: {
    maxHeight: "100%",
    maxWidth: "100%",
    overflow: "hidden",
    flex: 1,
    height: 2,
    left: 15,
  },
  // line: {
  //   height: 1,
  //   width: "90%",
  //   borderColor: Color.colorWhitesmoke_200,
  //   borderTopWidth: 1,
  //   borderStyle: "solid",
  //   position: "relative",
  // },
  forgotPassword: {
    color: Color.primaryDark,
    textAlign: "center",
    fontFamily: 'Urbanist-SemiBold',
    fontWeight: "700",
    lineHeight: 29,
    fontSize: FontSize.bodyLarge,
    width: 354,
    justifyContent: "center",
    display: "flex",
    alignItems: "center",
  },
  signUp: {
    marginLeft: 8,
    lineHeight: 26,
    fontSize: FontSize.bodyLarge,
    letterSpacing: 0,
    color: Color.primaryDark,
    textAlign: "center",
    fontFamily: 'Urbanist-SemiBold',
    fontWeight: "700",
  },
  frameIcon: {
    width: 23,
    height: 24,
    overflow: "hidden",
  },
  iconlyregularboldmessage: {
    top: 16,
    left: 280,
    width: 33,
    height: 28,
    position: "absolute",
  },

  buttonPosition: {
    width: "100%",
    position: "absolute",
    alignSelf: "center",
    bottom: 20,

  },
  checkbox: {
    margin: 8,
  },

  errorBar: {
    position: 'relative',
    top: 0,
    left: 0,
    right: 0,
    backgroundColor: 'red',
    padding: 10,
  },
  errorText: {
    color: 'white',
    textAlign: 'center',
    fontWeight: 'bold',
  },

  socialnetworks: {
    marginVertical: 15,

  }
});

export default SignIn;
