package ai.prodigies.predicta_api.security;

import io.jsonwebtoken.Jwts;
import io.jsonwebtoken.SignatureAlgorithm;
import io.jsonwebtoken.security.Keys;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;
import org.mockito.Mockito;
import org.springframework.mock.web.MockHttpServletRequest;
import org.springframework.mock.web.MockHttpServletResponse;
import org.springframework.mock.web.MockHttpSession;
import jakarta.servlet.http.HttpServletResponse;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.security.authentication.UsernamePasswordAuthenticationToken;

import javax.crypto.SecretKey;
import jakarta.servlet.FilterChain;
import jakarta.servlet.ServletException;
import java.io.IOException;
import java.nio.charset.StandardCharsets;
import java.util.Date;

import static org.assertj.core.api.Assertions.assertThat;
import static org.mockito.Mockito.*;
import static org.junit.jupiter.api.Assertions.*;



public class JwtAuthenticationFilterTest {

    private JwtAuthenticationFilter jwtAuthenticationFilter;
    private SecretKey secretKey;
    private FilterChain mockFilterChain;

    @BeforeEach
    void setUp() {
        secretKey = Keys.secretKeyFor(SignatureAlgorithm.HS256); // Shared key for signing
        jwtAuthenticationFilter = new JwtAuthenticationFilter(secretKey);
        mockFilterChain = mock(FilterChain.class);
        SecurityContextHolder.clearContext();
    }

    private String generateValidJwt(String subject) {

        return Jwts.builder()
                .setSubject(subject)
                .setIssuedAt(new Date())
                .setExpiration(new Date(System.currentTimeMillis() + 3600000)) // 1 hour expiration
                .signWith(secretKey, SignatureAlgorithm.HS256)
                .compact();
    }

    @Test
    void shouldAuthenticateWhenJwtIsValid() throws Exception {
        String validJwt = generateValidJwt("testuser");

        MockHttpServletRequest request = new MockHttpServletRequest();
        request.addHeader("Authorization", "Bearer " + validJwt);

        MockHttpServletResponse response = new MockHttpServletResponse();

        FilterChain filterChain = mock(FilterChain.class);

        jwtAuthenticationFilter.doFilterInternal(request, response, filterChain);

        assertNotNull(SecurityContextHolder.getContext().getAuthentication());
        assertEquals("testuser", SecurityContextHolder.getContext().getAuthentication().getName());
    }

    @Test
    void shouldNotAuthenticateWhenJwtIsInvalid() throws ServletException, IOException {
        // Arrange
        String invalidJwt = "invalid.jwt.token";
        MockHttpServletRequest request = new MockHttpServletRequest();
        request.addHeader("Authorization", "Bearer " + invalidJwt);

        MockHttpServletResponse response = new MockHttpServletResponse();

        // Act
        jwtAuthenticationFilter.doFilterInternal(request, response, mockFilterChain);

        // Assert
        assertThat(SecurityContextHolder.getContext().getAuthentication()).isNull();
        assertThat(response.getStatus()).isEqualTo(HttpServletResponse.SC_UNAUTHORIZED);
        assertThat(response.getContentAsString()).isEqualTo("Invalid JWT Token");

        verify(mockFilterChain, never()).doFilter(request, response);
    }

    @Test
    void shouldPassRequestThroughFilterWhenNoAuthorizationHeader() throws ServletException, IOException {
        // Arrange
        MockHttpServletRequest request = new MockHttpServletRequest();
        MockHttpServletResponse response = new MockHttpServletResponse();

        // Act
        jwtAuthenticationFilter.doFilterInternal(request, response, mockFilterChain);

        // Assert
        assertThat(SecurityContextHolder.getContext().getAuthentication()).isNull();
        verify(mockFilterChain, times(1)).doFilter(request, response);
    }

    @Test
    void shouldPassRequestThroughFilterWhenAuthorizationHeaderDoesNotStartWithBearer() throws ServletException, IOException {
        // Arrange
        MockHttpServletRequest request = new MockHttpServletRequest();
        request.addHeader("Authorization", "Basic somebase64value");

        MockHttpServletResponse response = new MockHttpServletResponse();

        // Act
        jwtAuthenticationFilter.doFilterInternal(request, response, mockFilterChain);

        // Assert
        assertThat(SecurityContextHolder.getContext().getAuthentication()).isNull();
        verify(mockFilterChain, times(1)).doFilter(request, response);
    }
}