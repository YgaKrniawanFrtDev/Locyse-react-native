// app/(tabs-menu)/scan.tsx
import { CameraView, useCameraPermissions, BarcodeScanningResult } from 'expo-camera';
import { StyleSheet, View, Text, TouchableOpacity, Alert, ActivityIndicator } from 'react-native';
import { LinearGradient } from 'expo-linear-gradient';
import { Ionicons } from '@expo/vector-icons';
import { useEffect, useState, useRef } from 'react';
import LottieView from 'lottie-react-native';

const API_URL = "http://10.162.74.31:8000/api/scan"; 

export default function ScanScreen() {
  const [permission, requestPermission] = useCameraPermissions();
  const [isCameraReady, setIsCameraReady] = useState(false);
  const [scannedToken, setScannedToken] = useState<string | null>(null);
  const [isLoading, setIsLoading] = useState(false);
  const [showSuccess, setShowSuccess] = useState(false);
  const [lastScannedTime, setLastScannedTime] = useState<number>(0);
  const [attendanceStatus, setAttendanceStatus] = useState<'masuk' | 'pulang'>('masuk');
  const [userId, setUserId] = useState<number | null>(null);
  const cameraRef = useRef<CameraView>(null);

  useEffect(() => {
    if (permission?.granted) setIsCameraReady(true);
  }, [permission]);

  useEffect(() => {
    setUserId(1);
  }, []);

  if (!permission) {
    return (
      <View style={styles.center}>
        <Text>Loading...</Text>
      </View>
    );
  }

  const handleBarcodeScanned = async (result: BarcodeScanningResult) => {
    const now = Date.now();

    if (now - lastScannedTime < 2500) return;
    if (isLoading) return;

    setLastScannedTime(now);

    const token = result.data;

    setScannedToken(token);
  };
  const submitAttendance = async () => {
    if (!scannedToken) return;

    setIsLoading(true);

    try {
      const response = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          token: scannedToken,
          user_id: userId,
          status: attendanceStatus,
        }),
      });

      const data = await response.json();

      if (data.success) {
        setShowSuccess(true);

        setTimeout(() => {
          setShowSuccess(false);
          setScannedToken(null); 
        }, 2000);
      } else {
        Alert.alert("Error", data.message || "Gagal menyimpan absensi");
      }

    } catch (err) {
      Alert.alert("Error", "Tidak bisa terhubung ke server");
    }

    setIsLoading(false);
  };

  const handleCancel = () => {
    setScannedToken(null);
  };

  const toggleStatus = () => {
    setAttendanceStatus(attendanceStatus === "masuk" ? "pulang" : "masuk");
  };

  if (!permission.granted) {
    return (
      <View style={styles.container}>
        <Text>Izinkan kamera untuk scan QR.</Text>
        <TouchableOpacity onPress={requestPermission} style={styles.permissionBtn}>
          <Text style={{ color: "#4F46E5" }}>Izinkan Sekarang</Text>
        </TouchableOpacity>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      
      <LinearGradient
        colors={["#7C3AED", "#4F46E5"]}
        start={{ x: 0, y: 0 }}
        end={{ x: 1, y: 1 }}
        style={styles.header}
      >
        <View style={styles.headerContent}>
          <View style={styles.profile}>
            <View>
              <Text style={styles.username}>Scan Barcode</Text>
              <Text style={styles.statusText}>
                Status: {attendanceStatus === "masuk" ? "🟢 Check In" : "🔴 Check Out"}
              </Text>
            </View>

            <TouchableOpacity style={styles.iconProfile} onPress={toggleStatus}>
              <View style={styles.iconContainer}>
                <Ionicons name="swap-vertical" size={30} color="#7C3AED" />
              </View>
            </TouchableOpacity>

          </View>
        </View>
      </LinearGradient>

      <View style={styles.body}>
        
        <Text style={styles.scanTitle}>Scan Barcode Absensi!</Text>

        <View style={styles.frameContainer}>
          <CameraView
            ref={cameraRef}
            style={styles.camera}
            facing="back"
            onCameraReady={() => setIsCameraReady(true)}
            onBarcodeScanned={!isLoading ? handleBarcodeScanned : undefined}
            barcodeScannerSettings={{
              barcodeTypes: ["qr", "code128", "code39", "ean13"],
            }}
          />

          {scannedToken && (
            <View style={styles.scannedOverlay}>
              <Text style={styles.scannedText}>
                Token: {scannedToken.substring(0, 10)}...
              </Text>
            </View>
          )}
        </View>

        {scannedToken && (
          <View style={styles.buttonGroup}>

            <TouchableOpacity
              style={styles.cancelButton}
              onPress={handleCancel}
              disabled={isLoading}
            >
              <Ionicons name="close" size={20} color={"#7C3AED"} />
              <Text style={styles.cancelText}>Batalkan</Text>
            </TouchableOpacity>

            <TouchableOpacity
              style={[styles.confirmButton, isLoading && styles.confirmButtonDisabled]}
              onPress={submitAttendance}
              disabled={isLoading}
            >
              {isLoading ? (
                <ActivityIndicator color="white" />
              ) : (
                <>
                  <Text style={styles.confirmText}>Absen Sekarang</Text>
                  <Ionicons name="checkmark" size={20} color={"white"} />
                </>
              )}
            </TouchableOpacity>

          </View>
        )}

        {showSuccess && (
          <View style={styles.successOverlay}>
            <LottieView
              source={require("../../assets/lottie/Scan QR Code Success.json")}
              autoPlay
              loop={false}
              style={styles.lottie}
            />
            <Text style={styles.successText}>Attendance Recorded!</Text>
          </View>
        )}

      </View>

      {!isCameraReady && (
        <View style={styles.loadingOverlay}>
          <ActivityIndicator size="large" color="#7C3AED" />
          <Text style={{ marginTop: 10 }}>Loading kamera...</Text>
        </View>
      )}
    </View>
  );
}


const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#F9FAFB',
  },
  header: {
    paddingTop: 50,
    height: 130,
    paddingBottom: 30,
    paddingHorizontal: 20,
    borderBottomLeftRadius: 20,
    borderBottomRightRadius: 20,
  },
  headerContent: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    gap: 10,
  },
  profile: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    width: '100%',
  },
  iconProfile: {
    backgroundColor: 'white',
    height: 50,
    width: 50,
    borderRadius: 50,
    elevation: 2,
  },
  iconContainer: {
    width: '100%',
    height: '100%',
    borderRadius: 16,
    justifyContent: 'center',
    alignItems: 'center',
  },
  username: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#FFF',
    marginTop: 4,
  },
  statusText: {
    fontSize: 12,
    color: '#E0D6FF',
    marginTop: 2,
  },

  body: {
    flex: 1,
    paddingHorizontal: 20,
    paddingTop: 40,
    alignItems: 'center',
  },
  scanTitle: {
    fontSize: 20,
    fontWeight: '800',
    color: '#2C3E50',
    marginBottom: 30,
    textAlign: 'center',
  },
  frameContainer: {
    width: 300,
    height: 300,
    borderWidth: 4,
    borderColor: '#ffffffff', 
    borderRadius: 16,
    overflow: 'hidden',
    justifyContent: 'center',
    alignItems: 'center',
    position: 'relative',
  },
  camera: {
    width: '100%',
    height: '100%',
  },
  scannedOverlay: {
    position: 'absolute',
    bottom: 10,
    left: 10,
    right: 10,
    backgroundColor: 'rgba(0, 0, 0, 0.7)',
    padding: 8,
    borderRadius: 8,
  },
  scannedText: {
    color: '#FFF',
    fontSize: 12,
    textAlign: 'center',
  },
  loadingOverlay: {
    position: 'absolute',
    top: 0,
    left: 0,
    right: 0,
    bottom: 0,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: 'rgba(249, 250, 251, 0.9)',
  },
  center: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    padding: 20,
  },
  permissionBtn: {
    marginTop: 20,
    paddingVertical: 10,
    paddingHorizontal: 20,
    backgroundColor: '#E0D6FF',
    borderRadius: 8,
  },
  buttonGroup: {
    flexDirection: 'row',
    gap: 10,
    marginTop: 32,
  },
  cancelButton: {
    flex: 1,
    paddingVertical: 14,
    borderWidth: 2,
    borderColor: '#7C3AED',
    borderRadius: 5,
    alignItems: 'center',
    justifyContent: 'center',
    flexDirection: 'row',
    shadowColor: '#000000',
    shadowOffset: {width: 2, height:10},
    shadowOpacity: 0.2,
    shadowRadius: 2,
    gap: 5,
  },
  cancelText: {
    color: '#7C3AED',
    fontWeight: '600',
    fontSize: 14,
  },
  confirmButton: {
    flex: 1,
    paddingVertical: 15,
    paddingHorizontal: 10,
    backgroundColor: '#7C3AED',
    borderRadius: 5,
    alignItems: 'center',
    flexDirection: 'row',
    justifyContent: 'center',
    height: 50,
    gap: 5,
    shadowColor: '#000000',
    shadowOffset: {width: 2, height:10},
    shadowOpacity: 0.2,
    shadowRadius: 2,
    elevation: 2,
  },
  confirmText: {
    color: 'white',
    fontWeight: '600',
    fontSize: 14,
  },
  confirmButtonDisabled: {
    opacity: 0.6,
  },
  successOverlay: {
    position: 'absolute',
    top: 0,
    left: 0,
    right: 0,
    bottom: 0,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: 'rgba(0, 0, 0, 0.7)',
    borderRadius: 16,
  },
  lottie: {
    width: 100,
    height: 100,
  },
  successText: {
    color: '#FFF',
    fontSize: 16,
    fontWeight: '600',
    marginTop: 10,
  },
});