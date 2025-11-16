import { View, Text, StyleSheet } from 'react-native'
import React from 'react'
import LottieView from 'lottie-react-native';
export default function scan() {
  return (
      <View style={styles.container}>
      <View style={StyleSheet.absoluteFill} />

      <LottieView
        source={require('../../assets/lottie/Scan QR Code Success.json')}
        autoPlay
        loop
        style={styles.animation}
      />
    </View>
  )
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: 'black',
    justifyContent: 'center',
    alignItems: 'center',
  },
  animation: {
    width: 250,
    height: 250,
  },
});
