

import * as React from "react";
import { Image } from "expo-image";
import { useNavigation, useRouter } from 'expo-router';
import { StyleSheet, Text, View } from "react-native";
import { Padding, Border, FontSize, Color, FontFamily } from "@/globals/Styles";
import useTranslation from "@/hooks/useTranslation";
import SocialNetwork from "@/components/SocialNetwork";
import Button from "@/components/Button";

import { LinearGradient } from 'expo-linear-gradient';


const Welcome = () => {
  const router = useRouter();
  const navigation = useNavigation();
  const { translate, isReady } = useTranslation();

  React.useEffect(() => {
    navigation.setOptions({ headerShown: false, title: "" });
  }, [navigation]);

  return (
    <LinearGradient
      colors={['#c7cffd', '#fcdee7']}
      style={{ flex: 1 }}
    >
      <View style={styles.container}>


        <View style={styles.container2}>
          <Image
            style={styles.httpslottiefilescomanimatIcon}
            contentFit="cover"
            source={require("@/assets/splashAnimation.png")}
          />
          <Text style={[styles.prodigies]}>
            Prodigies
          </Text>
          <Text style={[styles.version10]}>AI Assistants</Text>
          <View style={styles.buttonsection}></View>
          <Button onPress={() => { router.replace('./SignIn') }} disabled={false} invertColors={false} >
          {translate("login")}
          </Button>
          <Button onPress={() => { router.replace('./SignUp') }} disabled={false} invertColors={true} >
          {translate("signup")}
          </Button>
        </View>
        <View style={styles.socialnetworks}>
          <SocialNetwork />
        </View>
      </View></LinearGradient>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 16,
  },
  container2: {
    paddingTop: 120, // Adjust top padding if needed to ensure content is centered
    paddingBottom: 200, // Adjust bottom padding to ensure there's space for the bottom component
  },
  gradientIcon: {
    height: "124.14%",
    width: "275.58%",
    position: "absolute",
    top: "-12.02%",
    right: "-87.67%",
  },

  prodigies: {
    color: "#6972F0",
    fontSize: 64,
    fontFamily: FontFamily.roadRageRegular,
    textAlign: "center",
    marginTop: 40,
  },
  version10: {
    fontFamily: FontFamily.bodyMedium_size,
    color: "#555",
    textAlign: "right",
    fontSize: FontSize.bodyMedium_size,
    width: 300,
  },
  httpslottiefilescomanimatIcon: {
    width: 128,
    height: 128,
    alignSelf: 'center',
    top: 40,
  },
  socialnetworks: {
    bottom: 40,
    position: "absolute",
    alignSelf: 'center',
    width:350,
  },
  buttonsection: {
    marginTop: 60,
  }
});


export default Welcome;
