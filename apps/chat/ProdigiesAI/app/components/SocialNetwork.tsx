import * as React from "react";
import { Image } from "expo-image";
import { StyleSheet, Text, View } from "react-native";
import { FontFamily, FontSize, Color, Padding, Border } from "../globals/Styles";
import { Meta } from '@/services/Meta';
import { Google } from '@/services/Google';
import { TouchableOpacity } from "react-native-gesture-handler";
import useTranslation from '@/hooks/useTranslation';
import Line from "./line";
const SocialNetwork = () => {
  const { translate, isReady } = useTranslation();
  return (
    <View style={styles.socialLoginSection}>
      <View style={styles.orContinueTitle} >

        <Line color={Color.primaryColor}/>
        <Text style={styles.orContinueWithText}>{translate("orcontinuewith")}</Text>
        <Line color={Color.primaryColor}/>

      </View>
      <View style={styles.socialNetsSection}>
        <TouchableOpacity style={styles.SocialButtons} onPress={() => Google()}>
          <View style={styles.socialIconSection}>

            <View style={styles.networkIcon}>
              <Image
                style={styles.frameIcon}
                contentFit="cover"
                source={require("@/assets/google.png")}
              />
              <Text style={styles.continueWithSocialIcon}>
                Continue with Google
              </Text>
            </View>

          </View>
        </TouchableOpacity>
        <TouchableOpacity style={styles.SocialButtons} onPress={() => Meta()}>
        <View
          style={styles.socialIconSection}
        >
          <View style={styles.networkIcon}>
            <Image
              style={[styles.frameIcon, styles.iconLayout]}
              contentFit="cover"
              source={require("@/assets/apple.png")}
            />
            <Text style={styles.continueWithSocialIcon}>
              Continue with Apple
            </Text>
          </View>
        </View>
        </TouchableOpacity>
        <TouchableOpacity style={styles.SocialButtons} onPress={() => Meta()}>
        <View
          style={styles.socialIconSection}
        >
          
            <View style={styles.networkIcon}>
              <Image
                style={[styles.frameIcon, styles.iconLayout]}
                contentFit="cover"
                source={require("@/assets/meta.png")}
              />
              <Text style={styles.continueWithSocialIcon}>
                Continue with Facebook
              </Text>
            </View>
          
        </View>
        </TouchableOpacity>
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  orContinueTitle: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
    flexDirection: "row",
    overflow: 'hidden',
  },

  orContinueWithText: {
    fontWeight: "500",
    fontFamily: FontFamily.bodyLargeSemibold,
    color: "#6357FF",
    fontSize: FontSize.bodyLarge,
  },

  socialLoginSection: {
    // top: 75,
  },
  
  socialNetsSection: {
    flexDirection: "row",
    // alignContent:"center",
    justifyContent: "space-around",
  },

  SocialButtons: {  
    top: 15,
    // marginLeft: 22,
    margin:5,
    height:60,
    width:110,
    alignSelf:'center',
    
  },

  socialIconSection: {
    paddingVertical: Padding.p_lg,
    paddingHorizontal: Padding.p_13xl,
    borderWidth: 1,
    borderRadius: Border.br_81xl,
    borderColor: Color.colorWhitesmoke_200,
    borderStyle: "solid",
    alignItems: "center",
    justifyContent: "center",
    flex: 1,
    backgroundColor: Color.white,

  },
  continueWithSocialIcon: {
    marginLeft: 12,
    fontFamily: FontFamily.bodyLargeSemibold, // Ensure this is a valid font family name
    fontWeight: "600",
    letterSpacing: 0,
    textAlign: "left",
    display: "none", // Remove or replace with conditional rendering/opacity
    color: Color.greyscale900,
    lineHeight: 29,
    fontSize: FontSize.bodyLarge, // Ensure this is a numeric value
  },
  networkIcon: {
    alignItems: "center",
    flexDirection: "row",
    justifyContent: "center",
  },

  frameIcon: {
    width: 23,
    height: 24,
    overflow: "hidden",
  },

  iconLayout: {
    width: 24,
    height: 24,
  },

});

export default SocialNetwork;
