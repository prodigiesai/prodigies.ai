import {  StyleSheet } from 'react-native';

/* fonts */
export const FontFamily = {
  bodyXlarge: "Urbanist-Bold",
  bodyLarge: "Urbanist-Regular",
  bodyLargeSemibold: "Urbanist-SemiBold",
  h6Medium: "Urbanist-Medium",
  bodyXsmallMedium: "Urbanist-Medium",
  bodyMedium_size: "Urbanist-Regular",
  urbanistLight: "Urbanist-Light",
  roadRageRegular: "RoadRage-Regular"
};

/* font sizes */
export const FontSize = {
  bodyXsmall_size: 10,
  bodySmall_size: 12,
  bodyMedium_size: 14,
  bodyLarge: 16,
  h6_size: 18,
  h5_size: 20,
  h4_size: 24,
  h3_size: 32,  
};
/* Colors */
export const Color = {

  colorWhitesmoke_100: "#f7f8fa",
  colorWhitesmoke_200: "#eee",
  transparentGreen: "rgba(23, 206, 146, 0.08)",
  transparentBlack: "rgba(0, 0, 0, 0.5)",
  greyscale500: "#9e9e9e",
  greyscale700: "#616161",
  greyscale900: "#212121",
  primaryMain100: "#eee",
  primaryColor: "#7e92f8",
  primary50: "#e8faf4",
  primary900: "#17ce92",
  primaryDark: "#201d67",
  white: "#fff",
  cornflowerblue: "#7e92f8",
  black: "#000",
  lavender: "#e0e7f2",
  whitesmoke: "#f3f6f5",
  purple: "#6357FF",
  red: "#ff0000",

};
/* Paddings */
export const Padding = {
  p_8xs: 5,
  p_5xs: 8,
  p_3xs: 10,
  p_xs: 12,
  p_mini: 15,
  p_base: 16,
  p_lg: 18,
  p_xl: 20,
  p_5xl: 24,
  p_8xl: 27,
  p_13xl: 32,
  
};
/* border radiuses */
export const Border = {
  br_base: 16,
  br_31xl: 50,
  br_81xl: 100,
};







export const light_styles = StyleSheet.create({
  themeContainer: {
    backgroundColor: Color.white,
  },
  themeText: {
    color: Color.black,
  },
});

export const dark_styles = StyleSheet.create({
  themeContainer: {
    backgroundColor: Color.black,
  },
  themeText: {
    color: Color.white,
  },
});