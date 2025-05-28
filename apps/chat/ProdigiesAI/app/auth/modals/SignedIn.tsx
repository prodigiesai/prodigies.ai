import * as React from "react";
import { Image } from "expo-image";
import { StyleSheet, Text, View, Modal } from "react-native";
import { Color, FontSize, FontFamily, Padding } from "@/globals/Styles";

const SignedIn = ({ modalVisible= false, setModalVisible = () => {} }) => {
    return (
        <Modal
            animationType="slide"
            transparent={true}
            visible={modalVisible}
            presentationStyle='overFullScreen'
            onRequestClose={() => { setModalVisible }}
        >
            {/* Modal content here. You can import and render LightSignUpSuccessful.tsx content or another component as needed */}

            <View style={styles.lightSignInSuccessful}>
                <View style={[styles.darkfalseComponentmodal, styles.groupIconPosition]}>
                    <Image
                        style={styles.groupIcon4}
                        contentFit="cover"
                        source={require("@/assets/signinsuccessful.png")}
                    />
                    <View style={styles.autoLayoutVertical}>
                        <Text style={[styles.modalTitle, styles.buttonTypo]}>
                            Sign up Successful!
                        </Text>
                        <Text
                            style={[styles.loremIpsumDolor, styles.text2Layout]}
                        >{`Please wait...
You will be directed to the homepage.`}</Text>
                    </View>
                    {/* <Image
                        style={styles.frameIcon3}
                        contentFit="cover"
                        source={require("@/assets/frame.png")}
                    /> */}
                </View>
            </View>
        </Modal>);
};

const styles = StyleSheet.create({

    lightSignInSuccessful: {
        flex: 1,
        opacity: 15,
        backgroundColor: Color.transparentBlack

    },
    darkfalseComponentmodal: {
        marginTop: -241,
        marginLeft: -169.5,
        left: "50%",
        borderRadius: 40,
        width: 340,
        paddingTop: 40,
        paddingBottom: Padding.p_13xl,
        paddingHorizontal: Padding.p_13xl,
        top: "50%",
        justifyContent: "center",
        alignItems: "center",
        backgroundColor: Color.white,
        // backgroundColor: "white",
        shadowColor: "#000",
        shadowOffset: {
            width: 0,
            height: 2,
        },
        shadowOpacity: 0.25,
        shadowRadius: 4,
        elevation: 5,
    },

    groupIconPosition: {
        top: "50%",
        position: "absolute",
    },
    groupIcon4: {
        width: 186,
        height: 180,
    },
    autoLayoutVertical: {
        marginTop: 32,
        alignSelf: "stretch",
    },

    modalTitle: {
        alignSelf: "stretch",
        lineHeight: 38,
        fontSize: FontSize.bodyLarge,
    },
    buttonTypo: {
        color: Color.primary900,
        textAlign: "center",
        fontFamily: FontFamily.bodyLarge,
        fontWeight: "700",
    },


    loremIpsumDolor: {
        marginTop: 16,
        alignSelf: "stretch",
        fontFamily: FontFamily.bodyLarge,
        letterSpacing: 0,
        textAlign: "center",
        color: Color.greyscale900,
    },

    text2Layout: {
        lineHeight: 26,
        fontSize: FontSize.bodyLarge,
    },

    frameIcon3: {
        width: 60,
        marginTop: 32,
        height: 60,
        overflow: "hidden",
    },
});

export default SignedIn;