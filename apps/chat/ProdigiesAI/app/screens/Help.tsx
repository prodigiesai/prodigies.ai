
import React, { useEffect, useState } from 'react';
import { Text, StyleSheet, View, Image, StyleProp, ViewStyle, TextStyle, ImageStyle, TouchableOpacity } from "react-native";
import { LinearGradient } from "expo-linear-gradient";
import useTranslation from '@/hooks/useTranslation';
import { AntDesign } from '@expo/vector-icons';
import { useNavigation, useRouter } from 'expo-router';
import { FontFamily, FontSize, Color, Padding, Border } from "@/globals/Styles";
import { ScrollView } from 'react-native-gesture-handler';

// Define types for the FAQ item props
interface FAQItemProps {
    question: string;
    answer: string;
};

// Reusable FAQ Item Component with TypeScript Props
const FAQItem: React.FC<FAQItemProps> = ({ question, answer }) => (
    <View style={styles.faqItemContainer}>
        <View style={styles.autoLayoutHorizontal}>
            <Text style={styles.questionText}>{question}</Text>
            <AntDesign name="down" size={24} color={Color.greyscale500} />
        </View>
        <Text style={styles.answerText}>{answer}</Text>
    </View>
);

const Help: React.FC = () => {


    const navigation = useNavigation();
    const router = useRouter();
    const [error, setError] = useState('');
    const { translate, isReady } = useTranslation();
    useEffect(() => {
        navigation.setOptions({
            headerShown: true,
            title: translate("Help Center"),
            headerBackVisible: false,
            headerLeft: () => (
                <TouchableOpacity
                    style={styles.backButton}
                    onPress={() => router.back()}
                >
                    <AntDesign name="left" size={24} color={Color.greyscale500} />
                </TouchableOpacity>
            ),
        });
    }, [navigation, isReady, translate]);

    const faqData: FAQItemProps[] = [
        { question: 'What is Prodigies AI?', answer: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' },
        { question: 'Is the Prodigies AI App free?', answer: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' },
        { question: 'How can I use Prodigies AI?', answer: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' },
        { question: 'How can I log out from Prodigies AI?', answer: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' },
        { question: 'How to close Prodigies AI account?', answer: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' },
        { question: 'Question', answer: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' },
    ];
    return (
        
        <View style={styles.container}>
            <View style={[styles.autoLayoutHorizontal]}>
                <View style={styles.stateactiveStylenoneThem}>
                    <Text style={styles.faqTypo}>FAQ</Text>
                    <LinearGradient
                        style={[styles.rectangle, styles.rectangleSpaceBlock]}
                        locations={[0, 1]}
                        colors={["#7e92f8", "#6972f0"]}
                    />
                </View>
                <View style={styles.stateactiveStylenoneThem}>
                    <Text style={[styles.horizontalTab, styles.faqTypo]}>Contact us</Text>
                    <View style={[styles.autoLayoutVertical1, styles.rectangleSpaceBlock]}>
                        <View style={styles.rectangle1} />
                    </View>
                </View>
            </View>

            <ScrollView>
            <View style={[styles.autoLayoutHorizontal1]}>
                <LinearGradient
                    style={styles.sizemediumTypefilledIcon}
                    locations={[0, 1]}
                    colors={["#7e92f8", "#6972f0"]}
                >
                    <Text style={[styles.chips, styles.chipsTypo]}>General</Text>
                </LinearGradient>
                <View style={styles.sizemediumTypeborderIcon}>
                    <Text style={[styles.account, styles.chipsTypo]}>Account</Text>
                </View>
                <View style={styles.sizemediumTypeborderIcon}>
                    <Text style={[styles.account, styles.chipsTypo]}>Service</Text>
                </View>
                <View style={styles.sizemediumTypeborderIcon}>
                    <Text style={[styles.account, styles.chipsTypo]}>Chatbot</Text>
                </View>
            </View>
            </ScrollView>
            <View style={styles.statedefaultSearchDarkfal}>
                <AntDesign name="search1" size={24} color={Color.greyscale500} />
                <Text style={[styles.search, styles.searchFlexBox]}>Search</Text>
                <View style={[styles.iconlyregularoutlinefilter, styles.iconlyregularoutlinesearchLayout]} />
            </View>
            {faqData.map((item, index) => (
                <FAQItem key={index} question={item.question} answer={item.answer} />
            ))}
        </View>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: Color.white,

    },
    // Define styles here
    autoLayoutVertical: {
        paddingTop: Padding.p_5xs,
        paddingBottom: Padding.p_5xs,
        marginVertical: 6,
        marginHorizontal: 18,
    },

    rectangleSpaceBlock: {
        marginTop: 12,
        alignSelf: "stretch"
    },
    faqTypo: {
        textAlign: "center",
        fontFamily: "Urbanist-SemiBold",
        fontWeight: "600",
        lineHeight: 29,
        fontSize: 18,
        letterSpacing: 0,
    },
    chipsTypo: {
        lineHeight: 26,
        fontSize: 16,
        textAlign: "center",
        fontFamily: "Urbanist-SemiBold",
        fontWeight: "600",
        letterSpacing: 0
    },
    searchFlexBox: {
        textAlign: "left",
        lineHeight: 29,
        fontSize: 18,
        flex: 1
    },
    iconlyregularoutlinesearchLayout: {
        height: 20,
        width: 20
    },
    darkfalseIconLayout: {
        marginTop: 16,
        overflow: "hidden",
        maxWidth: "100%",
        width: "100%",
    },
    elementsfaqBorder: {
        padding: 24,
        borderWidth: 1,
        borderColor: "#eee",
        backgroundColor: "#fff",
        borderRadius: 16,
        borderStyle: "solid",
        alignItems: "center",
        flexDirection: "row",
    },
    loremTypo: {
        color: "#424242",
        fontFamily: "Urbanist-Medium",
        fontWeight: "500",
        lineHeight: 22,
        fontSize: 14,
        marginTop: 16,
    },
    rectangle: {
        height: 4,
        backgroundColor: "transparent",
        marginTop: 12,
        borderRadius: 100

    },
    stateactiveStylenoneThem: {
        alignItems: "center",
        flex: 1
    },
    horizontalTab: {
        color: "#9e9e9e"
    },
    rectangle1: {
        backgroundColor: "#eee",
        height: 2,
        borderRadius: 100,
        alignSelf: "stretch"
    },
    autoLayoutVertical1: {
        justifyContent: "flex-end",
        paddingHorizontal: 0,
        paddingVertical: 1,
        alignItems: "center"
    },
    autoLayoutHorizontal: {
        flexDirection: "row",
        paddingTop: Padding.p_5xs,
        paddingBottom: Padding.p_5xs,
        marginVertical: 6,
        marginHorizontal: 18,
    },
    chips: {
        color: "#fff"
    },
    sizemediumTypefilledIcon: {
        paddingVertical: 8,
        paddingHorizontal: 20,
        justifyContent: "center",
        backgroundColor: "transparent",
        borderRadius: 100,
        alignItems: "center",
        flexDirection: "row"
    },
    account: {
        color: "#201d67"
    },
    sizemediumTypeborderIcon: {
        borderColor: "#e0e7f2",
        borderWidth: 2,
        marginLeft: 12,
        borderStyle: "solid",
        paddingVertical: 8,
        paddingHorizontal: 20,
        justifyContent: "center",
        borderRadius: 100,
        alignItems: "center",
        flexDirection: "row"
    },
    autoLayoutHorizontal1: {
        flexDirection: "row",
        marginTop: 10,
        paddingTop: Padding.p_5xs,
        paddingBottom: Padding.p_5xs,
        marginVertical: 6,
        marginHorizontal: 18,

    },
    search: {
        fontFamily: "Urbanist-Regular",
        color: "#43425c",
        marginLeft: 12,
        letterSpacing: 0,
        textAlign: "left"
    },
    iconlyregularoutlinefilter: {
        display: "none",
        marginLeft: 12
    },
    statedefaultSearchDarkfal: {
        backgroundColor: "rgba(114, 128, 243, 0.08)",
        paddingVertical: 18,
        borderRadius: 16,
        paddingHorizontal: 20,
        // marginTop: 24,
        alignItems: "center",
        flexDirection: "row",
        alignSelf: "stretch"
    },
    whatIsBrainybox: {
        fontWeight: "700",
        fontFamily: "Urbanist-Bold",
        color: "#212121"
    },
    iconlyregularboldarrowDo: {
        width: 24,
        height: 24,
        marginLeft: 12
    },
    autoLayoutHorizontal2: {
        alignItems: "center",
        flexDirection: "row"
    },
    darkfalseComponentdividerIcon: {
        maxHeight: "100%"
    },
    darkfalseComponentdividerIcon1: {
        height: 0,
        display: "none"
    },
    loremIpsumDolor1: {
        display: "none"
    },
    elementsfaqDarkfalseComp1: {
        marginTop: 20
    },
    backButton: {
        marginRight: Padding.p_8xs,
        marginLeft: Padding.p_5xs,
        width: 28,
        height: 28,
    },
    // Define styles here
    faqItemContainer: {
        padding: 24,
        borderWidth: 1,
        borderColor: "#eee",
        borderStyle: "solid",
        backgroundColor: "#fff",
        borderRadius: 16,
        marginTop: 20,
        alignItems: "center",
        flexDirection: "row",
        alignSelf: "stretch",
    },
    questionText: {
        fontSize: 18,
        lineHeight: 29,
        fontWeight: "700",
        fontFamily: "Urbanist-Bold",
        color: "#212121",
        textAlign: "left",
        flex: 1,
    },
    arrowIcon: {
        width: 24,
        height: 24,
        marginLeft: 12,
    },
    //   autoLayoutHorizontal: {
    //     alignItems: "center",
    //     flexDirection: "row",
    //     alignSelf: "stretch",
    //   },
    dividerIcon: {
        alignSelf: "stretch",
        marginTop: 16,
        maxHeight: "100%",
        overflow: "hidden",
        maxWidth: "100%",
        width: "100%",
    },
    answerText: {
        marginTop: 16,
        alignSelf: "stretch",
        color: "#424242",
        fontFamily: "Urbanist-Medium",
        fontWeight: "500",
        lineHeight: 22,
        letterSpacing: 0,
        fontSize: 14,
        textAlign: "left",
    },
    //   autoLayoutVertical: {
    //     width: "100%",
    //     alignSelf: "stretch",
    //     flex: 1,
    //   },
    //   backButton: {
    //     marginRight: Padding.p_8xs,
    //     marginLeft: Padding.p_5xs,
    //     width: 28,
    //     height: 28,
    //   },
});

export default Help;
